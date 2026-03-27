<?php

namespace App\Http\Controllers;

use App\Models\AboutPageContent;
use App\Models\HomePageContent;
use Illuminate\Http\Request;

class AdminContentController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'home-page');

        $homePageContent = HomePageContent::query()->firstOrCreate([], [
            'content' => HomePageContent::defaults(),
        ]);

        $aboutPageContent = AboutPageContent::query()->firstOrCreate([], [
            'content' => AboutPageContent::defaults(),
        ]);

        return view('admin.content-management', [
            'tab' => $tab,
            'homePageContent' => $homePageContent->normalizedContent(),
            'aboutPageContent' => $aboutPageContent->normalizedContent(),
        ]);
    }

    public function update(Request $request)
    {
        $page = $request->input('page');

        if ($page === 'home-page') {
            $validated = $request->validate([
                'content.hero.title' => ['required', 'string', 'max:255'],
                'content.hero.highlight' => ['required', 'string', 'max:255'],
                'content.hero.description' => ['required', 'string'],
                'content.hero.register_button' => ['required', 'string', 'max:255'],
                'content.hero.browse_button' => ['required', 'string', 'max:255'],
                'content.stats.0.value' => ['required', 'string', 'max:255'],
                'content.stats.0.label' => ['required', 'string', 'max:255'],
                'content.stats.1.value' => ['required', 'string', 'max:255'],
                'content.stats.1.label' => ['required', 'string', 'max:255'],
                'content.stats.2.value' => ['required', 'string', 'max:255'],
                'content.stats.2.label' => ['required', 'string', 'max:255'],
                'content.stats.3.value' => ['required', 'string', 'max:255'],
                'content.stats.3.label' => ['required', 'string', 'max:255'],
                'content.featured.title' => ['required', 'string', 'max:255'],
                'content.featured.subtitle' => ['required', 'string', 'max:255'],
                'content.how_it_works.title' => ['required', 'string', 'max:255'],
                'content.how_it_works.subtitle' => ['required', 'string', 'max:255'],
                'content.how_it_works.steps.0.title' => ['required', 'string', 'max:255'],
                'content.how_it_works.steps.0.description' => ['required', 'string'],
                'content.how_it_works.steps.1.title' => ['required', 'string', 'max:255'],
                'content.how_it_works.steps.1.description' => ['required', 'string'],
                'content.how_it_works.steps.2.title' => ['required', 'string', 'max:255'],
                'content.how_it_works.steps.2.description' => ['required', 'string'],
                'content.cta.title' => ['required', 'string', 'max:255'],
                'content.cta.description' => ['required', 'string'],
                'content.cta.button' => ['required', 'string', 'max:255'],
            ]);

            $homePageContent = HomePageContent::query()->firstOrCreate([], [
                'content' => HomePageContent::defaults(),
            ]);

            $homePageContent->update([
                'content' => array_replace_recursive($homePageContent->content ?? [], $validated['content'] ?? []),
            ]);

            return redirect()
                ->route('admin.content-management', ['tab' => 'home-page'])
                ->with('success', 'Home page content updated successfully.');
        }

        if ($page === 'about-page') {
            $validated = $request->validate([
                'content.header.title' => ['required', 'string', 'max:255'],
                'content.header.subtitle' => ['required', 'string', 'max:255'],
                'content.mission.title' => ['required', 'string', 'max:255'],
                'content.mission.description' => ['required', 'string'],
                'content.offers.0.title' => ['required', 'string', 'max:255'],
                'content.offers.0.description' => ['required', 'string'],
                'content.offers.1.title' => ['required', 'string', 'max:255'],
                'content.offers.1.description' => ['required', 'string'],
                'content.offers.2.title' => ['required', 'string', 'max:255'],
                'content.offers.2.description' => ['required', 'string'],
                'content.offers.3.title' => ['required', 'string', 'max:255'],
                'content.offers.3.description' => ['required', 'string'],
                'content.values.title' => ['required', 'string', 'max:255'],
                'content.values.items.0' => ['required', 'string'],
                'content.values.items.1' => ['required', 'string'],
                'content.values.items.2' => ['required', 'string'],
            ]);

            $aboutPageContent = AboutPageContent::query()->firstOrCreate([], [
                'content' => AboutPageContent::defaults(),
            ]);

            $aboutPageContent->update([
                'content' => array_replace_recursive($aboutPageContent->content ?? [], $validated['content'] ?? []),
            ]);

            return redirect()
                ->route('admin.content-management', ['tab' => 'about-page'])
                ->with('success', 'About page content updated successfully.');
        }

        abort(404);
    }
}
