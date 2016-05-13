<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CTLicenseCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ct_license_categories')->delete();
        DB::table('ct_license_categories')->insert([
            ["id" => 1, "key"=>"ambulatory_surgical_centers_recovery_care_centers", "name" =>"Ambulatory Surgical Centers/Recovery Care Centers", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 2, "key"=>"controlled_substances_practitioners_labs_manufacturers", "name" =>"Controlled Substances (Practitioners, Labs, Manufacturers)", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 3, "key"=>"child_day_care_licensing_program", "name" =>"Child Day Care Licensing Program", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 4, "key"=>"drug_control_pharmacy_pharmacists_etc", "name" =>"Drug Control - Pharmacy, Pharmacists, etc.", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 5, "key"=>"emergency_medical_services", "name" =>"Emergency Medical Services", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 6, "key"=>"healthcare_practitioners", "name" =>"Healthcare Practitioners", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 7, "key"=>"hemodialysis_centers", "name" =>"Hemodialysis Centers", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 8, "key"=>"hospitals", "name" =>"Hospitals", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 9, "key"=>"infirmaries_clinics", "name" =>"Infirmaries/Clinics", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 10, "key"=>"long_term_care_assisted_living_facilities_home_health_care_hospice", "name" =>"Long-Term Care/Assisted Living Facilities/Home Health Care/Hospice", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 11, "key"=>"medical_marijuana_producer_dispensary_facility_dispensary_etc", "name" =>"Medical Marijuana -Producer, Dispensary Facility, Dispensary, etc", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 12, "key"=>"mental_health_care", "name" =>"Mental Health Care", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 13, "key"=>"registered_sanitarians", "name" =>"Registered Sanitarians", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
            ["id" => 14, "key"=>"substance_abuse_care", "name" =>"Substance Abuse Care", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")],
        ]);
    }
}
