<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
/**
 * This will send all common data to view page.
 *
 *  @author	Anirban Saha
 */
class ViewVarriables extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {


        $site_settings = Cache::remember('site_settings', 60*24, function () {
			  return SiteSetting::select('site_title','image','admin_email')->first();
		});
        View::share('site_name', $site_settings->site_title);
        View::share('site_logo', $site_settings->image);
        View::share('admin_email', $site_settings->admin_email);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
