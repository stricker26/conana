<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        //URL::forceScheme('https');

        //hq sidebar
        view()->composer('dashboard.layouts.sidebar', function($view){
            $provinces = DB::table('province')->get();
            $regions = array();
            foreach($provinces as $prov_region) {
                if(!in_array($prov_region->region, $regions)) {
                    array_push($regions, $prov_region->region);
                }
            }
            sort($regions);

            //over all status
            $pending_count_all = count(DB::table('candidates')->where('signed_by_lp',NULL)->get());
            $approved_count_all = count(DB::table('candidates')->where('signed_by_lp',1)->get());
            $rejected_count_all = count(DB::table('candidates')->where('signed_by_lp',2)->get());

            //regional status
            $pending_count_region = (object)[];
            $approved_count_region = (object)[];
            $rejected_count_region = (object)[];
            foreach($regions as $region){
                $province_id = DB::table('province')
                                ->select('province_code')
                                ->where('region',$region)
                                ->get();

                $count_p = 0;
                $count_a = 0;
                $count_r = 0;
                foreach($province_id as $id_province){
                    $count = count(DB::table('candidates')
                        ->where('province_id',$id_province->province_code)
                        ->where('signed_by_lp',NULL)
                        ->get());
                    $count_p += $count;
                    
                    $count = count(DB::table('candidates')
                                ->where('province_id',$id_province->province_code)
                                ->where('signed_by_lp',1)
                                ->get());
                    $count_a += $count;

                    $count = count(DB::table('candidates')
                                ->where('province_id',$id_province->province_code)
                                ->where('signed_by_lp',2)
                                ->get());
                    $count_r += $count;
                }

                $pending_count_region->$region = $count_p;
                $approved_count_region->$region = $count_a;
                $rejected_count_region->$region = $count_r;
            }

            //provincial status
            $pending_count_province = (object)[];
            $approved_count_province = (object)[];
            $rejected_count_province = (object)[];
            foreach($regions as $region){
                $province_id = DB::table('province')
                                ->where('region',$region)
                                ->get();

                foreach($province_id as $id_province) {
                    if($id_province->type === 'HUC' && $region !== 'NCR') {
                        $candidates_HUC = DB::table('candidates')
                            ->where('province_id',$id_province->province_code)
                            ->where('signed_by_lp',NULL)
                            ->get();
                        foreach($candidates_HUC as $candidate_HUC) {
                            $candidate_HUC_array = explode("-", $candidate_HUC->province_id);
                            $province_key = DB::table('province')
                                ->where('province_code',$candidate_HUC_array[0])
                                ->first()->lgu;
                            if(!isset($pending_count_province->$province_key)) {
                                $pending_count_province->$province_key = 1;
                            } else {
                                $pending_count_province->$province_key += 1;
                            }
                        }

                        $candidates_HUC = DB::table('candidates')
                            ->where('province_id',$id_province->province_code)
                            ->where('signed_by_lp',1)
                            ->get();
                        foreach($candidates_HUC as $candidate_HUC) {
                            $candidate_HUC_array = explode("-", $candidate_HUC->province_id);
                            $province_key = DB::table('province')
                                ->where('province_code',$candidate_HUC_array[0])
                                ->first()->lgu;
                            if(!isset($approved_count_province->$province_key)) {
                                $approved_count_province->$province_key = 1;
                            } else {
                                $approved_count_province->$province_key += 1;
                            }
                        }

                        $candidates_HUC = DB::table('candidates')
                            ->where('province_id',$id_province->province_code)
                            ->where('signed_by_lp',2)
                            ->get();
                        foreach($candidates_HUC as $candidate_HUC) {
                            $candidate_HUC_array = explode("-", $candidate_HUC->province_id);
                            $province_key = DB::table('province')
                                ->where('province_code',$candidate_HUC_array[0])
                                ->first()->lgu;
                            if(!isset($rejected_count_province->$province_key)) {
                                $rejected_count_province->$province_key = 1;
                            } else {
                                $rejected_count_province->$province_key += 1;
                            }
                        }
                    } else {
                        $count = count(DB::table('candidates')
                            ->where('province_id',$id_province->province_code)
                            ->where('signed_by_lp',NULL)
                            ->get());
                        $province_key = $id_province->lgu;
                        if(!isset($pending_count_province->$province_key)) {
                            $pending_count_province->$province_key = $count;
                        } else {
                            $pending_count_province->$province_key += $count;
                        }

                        $count = count(DB::table('candidates')
                            ->where('province_id',$id_province->province_code)
                            ->where('signed_by_lp',1)
                            ->get());
                        $province_key = $id_province->lgu;
                        if(!isset($approved_count_province->$province_key)) {
                            $approved_count_province->$province_key = $count;
                        } else {
                            $approved_count_province->$province_key += $count;
                        }


                        $count = count(DB::table('candidates')
                            ->where('province_id',$id_province->province_code)
                            ->where('signed_by_lp',2)
                            ->get());
                        $province_key = $id_province->lgu;
                        if(!isset($rejected_count_province->$province_key)) {
                            $rejected_count_province->$province_key = $count;
                        } else {
                            $rejected_count_province->$province_key += $count;
                        }
                    }
                }
            }

            $view->with(compact(
                'provinces',
                'regions',
                'pending_count_all',
                'approved_count_all',
                'rejected_count_all',
                'pending_count_region',
                'approved_count_region',
                'rejected_count_region',
                'pending_count_province',
                'approved_count_province',
                'rejected_count_province'
            ));
        });
        
        //lec sidebar
        view()->composer('lec.layouts.sidebar', function($view){
            $userId = Auth::user()->id;
            $lec = DB::table('lec')->where('user', '=', $userId)->orWhere('user_2', '=', $userId)->first();
            $lecId = $lec->id;
            $provinces = DB::table('province')->where('lec', '=', $lecId)->get();
            $regions = array();
            foreach($provinces as $prov_region) {
                if(!in_array($prov_region->region, $regions)) {
                    array_push($regions, $prov_region->region);
                }
            }
            sort($regions);

            //over all status
            $pending_count_all = 0;
            $approved_count_all = 0;
            $rejected_count_all = 0;
            foreach($provinces as $province_id) {
                $pending_a = count(DB::table('candidates')
                                    ->where('signed_by_lp',NULL)
                                    ->where('province_id',$province_id->province_code)
                                    ->get());
                $pending_count_all = $pending_count_all + $pending_a;

                $approved_a = count(DB::table('candidates')
                                    ->where('signed_by_lp',1)
                                    ->where('province_id',$province_id->province_code)
                                    ->get());
                $approved_count_all = $approved_count_all + $approved_a;

                $rejected_a = count(DB::table('candidates')
                                    ->where('signed_by_lp',2)
                                    ->where('province_id',$province_id->province_code)
                                    ->get());
                $rejected_count_all = $rejected_count_all + $rejected_a;
            }

            //regional status
            $pending_count_region = array();
            $approved_count_region = array();
            $rejected_count_region = array();
            foreach($regions as $region){
                foreach($provinces as $province) {
                    if($province->region === $region) {
                        $count_p = 0;
                        $count_a = 0;
                        $count_r = 0;
                        if(count(DB::table('candidates')
                                    ->where('province_id',$province->province_code)
                                    ->where('signed_by_lp',NULL)
                                    ->get()) !== 0) {
                            $count_p++;
                        }
                        
                        if(count(DB::table('candidates')
                                    ->where('province_id',$province->province_code)
                                    ->where('signed_by_lp',1)
                                    ->get()) !== 0) {
                            $count_a++;
                        }

                        if(count(DB::table('candidates')
                                    ->where('province_id',$province->province_code)
                                    ->where('signed_by_lp',2)
                                    ->get()) !== 0) {
                            $count_r++;
                        }

                        if($count_p === 0){
                            array_push($pending_count_region, 0);
                        } else {
                            array_push($pending_count_region, $count_p);
                        }

                        if($count_a === 0){
                            array_push($approved_count_region, 0);
                        } else {
                            array_push($approved_count_region, $count_a);
                        }
                  
                        if($count_r === 0){
                            array_push($rejected_count_region, 0);
                        } else {
                            array_push($rejected_count_region, $count_r);
                        }
                    }
                }
            }

            //provincial status
            $pending_count_province = (object)[];
            $approved_count_province = (object)[];
            $rejected_count_province = (object)[];
            foreach($regions as $region){
                $province_id = DB::table('province')
                                ->select('province_code')
                                ->where('region',$region)
                                ->get();
                $array_p = array();
                $array_a = array();
                $array_r = array();
                foreach($province_id as $id_province) {
                    $count = count(DB::table('candidates')
                                ->where('province_id',$id_province->province_code)
                                ->where('signed_by_lp',NULL)
                                ->get());
                    if($count !== 0) {
                        array_push($array_p, $count);
                    } else {
                        array_push($array_p, 0);
                    }

                    $count = count(DB::table('candidates')
                                ->where('province_id',$id_province->province_code)
                                ->where('signed_by_lp',1)
                                ->get());
                    if($count !== 0) {
                        array_push($array_a, $count);
                    } else {
                        array_push($array_a, 0);
                    }

                    $count = count(DB::table('candidates')
                                ->where('province_id',$id_province->province_code)
                                ->where('signed_by_lp',2)
                                ->get());
                    if($count !== 0) {
                        array_push($array_r, $count);
                    } else {
                        array_push($array_r, 0);
                    }

                    $pending_count_province->$region = $array_p;
                    $approved_count_province->$region = $array_a;
                    $rejected_count_province->$region = $array_r;
                }
            }
            $view->with(compact(
                'provinces',
                'regions',
                'pending_count_all',
                'approved_count_all',
                'rejected_count_all',
                'pending_count_region',
                'approved_count_region',
                'rejected_count_region',
                'pending_count_province',
                'approved_count_province',
                'rejected_count_province'
            ));
        });

        view()->composer('lec.lec', function($view){
            $userId = Auth::user()->id;
            $lec = DB::table('lec')->where('user', '=', $userId)->orWhere('user_2', '=', $userId)->first();
            $lecId = $lec->id;
            $provinces = DB::table('province')->where('lec', '=', $lecId)->get();
            $regions = array();
            $municipalities = array();
            $cities = array();
            foreach($provinces as $prov_region) {
                if(!in_array($prov_region->region, $regions)) {
                    array_push($regions, $prov_region->region);
                }

                $municipality_table = DB::table('municipality')->where('province_code',$prov_region->province_code)->get()->toArray();
                if(count($municipality_table) !== 0) {
                    array_merge($municipalities, $municipality_table);
                }

                $city_table = DB::table('city')->where('province_code',$prov_region->province_code)->get()->toArray();
                if(count($city_table) !== 0) {
                    array_merge($cities, $city_table);
                }
            }
            sort($regions);

            $view->with(compact(
                'provinces',
                'regions',
                'municipalities',
                'cities'
            ));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
