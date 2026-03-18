#!/usr/bin/env node
const puppeteer = require('puppeteer');

const [,, targetUrl] = process.argv;
if (!targetUrl) {
    console.error('Missing target URL');
    process.exit(1);
}

(async () => {
    const executablePath =
        process.env.PUPPETEER_EXECUTABLE_PATH ||
        puppeteer.executablePath();

(async () => {
    const browser = await puppeteer.launch({
        executablePath,
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
        defaultViewport: { width: 1200, height: 1600 },
    });
    try {
        const page = await browser.newPage();
        await page.goto(targetUrl, { waitUntil: 'networkidle0' });
        await page.waitForTimeout(750);
        const pdfBuffer = await page.pdf({
            format: 'A4',
            printBackground: true,
            margin: {
                top: '12mm',
                bottom: '12mm',
                left: '12mm',
                right: '12mm',
            },
        });
        process.stdout.write(pdfBuffer.toString('base64'));
    } catch (err) {
        console.error(err);
        process.exit(1);
    } finally {
        await browser.close();
    }
})();
