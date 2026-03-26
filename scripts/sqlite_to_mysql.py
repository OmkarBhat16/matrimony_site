#!/usr/bin/env python3
"""Transpile a SQLite SQL dump into MySQL-compatible SQL using sqlglot."""

from __future__ import annotations

import argparse
import re
import sys
from pathlib import Path

from sqlglot import transpile
from sqlglot.errors import ErrorLevel


def normalize_unistr(sql: str) -> str:
    """
    Replace UNISTR('...') with a plain string literal and translate \u000a to \n.
    MySQL doesn't support UNISTR, and our dump uses it for newlines.
    """

    def repl(match: re.Match[str]) -> str:
        inner = match.group(1)
        inner = inner.replace("\\u000a", "\\n")
        return "'" + inner + "'"

    return re.sub(r"(?i)unistr\('([^']*)'\)", repl, sql)


def strip_sqlite_artifacts(
    sql: str,
    drop_pragmas: bool = True,
    drop_sqlite_sequence: bool = True,
) -> str:
    lines: list[str] = []
    for line in sql.splitlines():
        if drop_pragmas and re.match(r"^\s*PRAGMA\b", line, flags=re.I):
            continue
        if drop_sqlite_sequence and re.search(r"\bsqlite_sequence\b", line, flags=re.I):
            continue
        lines.append(line)
    return "\n".join(lines)


def to_mysql(sql: str, read: str = "sqlite", write: str = "mysql") -> str:
    statements = transpile(
        sql,
        read=read,
        write=write,
        error_level=ErrorLevel.IGNORE,
    )
    statements = [stmt.strip() for stmt in statements if stmt and stmt.strip()]
    if not statements:
        return ""
    return ";\n".join(statements) + ";\n"


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(
        description="Transpile a SQLite SQL dump to MySQL using sqlglot.",
    )
    parser.add_argument(
        "-i",
        "--input",
        default="database/sqlite_dump.sql",
        help="Path to the SQLite dump (default: database/sqlite_dump.sql)",
    )
    parser.add_argument(
        "-o",
        "--output",
        default="database/mysql_dump.sql",
        help="Path to write the MySQL dump (default: database/mysql_dump.sql)",
    )
    parser.add_argument(
        "--read",
        default="sqlite",
        help="sqlglot input dialect (default: sqlite)",
    )
    parser.add_argument(
        "--write",
        default="mysql",
        help="sqlglot output dialect (default: mysql)",
    )
    parser.add_argument(
        "--no-drop-pragmas",
        action="store_true",
        help="Keep PRAGMA statements in the output.",
    )
    parser.add_argument(
        "--no-drop-sqlite-sequence",
        action="store_true",
        help="Keep sqlite_sequence statements in the output.",
    )
    parser.add_argument(
        "--no-normalize-unistr",
        action="store_true",
        help="Keep UNISTR(...) calls unchanged.",
    )
    parser.add_argument(
        "--wrap-foreign-key-checks",
        action="store_true",
        help="Wrap output in SET FOREIGN_KEY_CHECKS=0/1.",
    )
    return parser


def main() -> None:
    parser = build_parser()
    args = parser.parse_args()

    input_path = Path(args.input)
    if not input_path.exists():
        sys.exit(f"Input file not found: {input_path}")

    sql = input_path.read_text()

    if not args.no_normalize_unistr:
        sql = normalize_unistr(sql)

    sql = strip_sqlite_artifacts(
        sql,
        drop_pragmas=not args.no_drop_pragmas,
        drop_sqlite_sequence=not args.no_drop_sqlite_sequence,
    )

    out_sql = to_mysql(sql, read=args.read, write=args.write)

    if args.wrap_foreign_key_checks:
        out_sql = "SET FOREIGN_KEY_CHECKS=0;\n" + out_sql + "SET FOREIGN_KEY_CHECKS=1;\n"

    output_path = Path(args.output)
    output_path.write_text(out_sql)

    print(f"Wrote {output_path}")


if __name__ == "__main__":
    main()
