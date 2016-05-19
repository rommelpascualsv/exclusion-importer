<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CTLicenseTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ct_license_types')->delete();
        DB::table('ct_license_types')->insert([
            ["key"=>'ambulatory_surgical_centers_recovery_care_centers.ambulatory_surgical_center', "name" =>"Ambulatory Surgical Center", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 1],
            ["key"=>'ambulatory_surgical_centers_recovery_care_centers.recovery_care_centers', "name" =>"Recovery Care Centers", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 1],
            ["key"=>'controlled_substances_practitioners_labs_manufacturers.controlled_substance_laboratories', "name" =>"Controlled Substance Laboratories", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 2],
            ["key"=>'controlled_substances_practitioners_labs_manufacturers.controlled_substance_registration_for_practitioners', "name" =>"Controlled Substance Registration for Practitioners", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 2],
            ["key"=>'controlled_substances_practitioners_labs_manufacturers.manufacturers_of_drugs_cosmetics_and_medical_devices', "name" =>"Manufacturers of Drugs, Cosmetics and Medical Devices", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 2],
            ["key"=>'controlled_substances_practitioners_labs_manufacturers.wholesalers_of_drugs_cosmetics_and_medical_devices', "name" =>"Wholesalers of Drugs, Cosmetics and Medical Devices", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 2],
            ["key"=>'child_day_care_licensing_program.child_day_care_centers_and_group_day_care_homes_closed_1_year', "name" =>"Child Day Care Centers and Group Day Care Homes Closed - 1 Year", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 3],
            ["key"=>'child_day_care_licensing_program.child_day_care_centers_and_group_day_care_homes_closed_60_days', "name" =>"Child Day Care Centers and Group Day Care Homes Closed - 60 Days", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 3],
            ["key"=>'child_day_care_licensing_program.child_day_care_centers_and_group_day_care_homes_opened_1_year', "name" =>"Child Day Care Centers and Group Day Care Homes Opened - 1 Year", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 3],
            ["key"=>'child_day_care_licensing_program.child_day_care_centers_and_group_day_care_homes_opened_60_days', "name" =>"Child Day Care Centers and Group Day Care Homes Opened - 60 Days", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 3],
            ["key"=>'child_day_care_licensing_program.child_day_care_centers_and_group_day_care_homes_total_by_date_active', "name" =>"Child Day Care Centers and Group Day Care Homes Total by Date (Active)", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 3],
            ["key"=>'child_day_care_licensing_program.family_day_care_homes_closed_1_year', "name" =>"Family Day Care Homes Closed - 1 Year", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 3],
            ["key"=>'child_day_care_licensing_program.family_day_care_homes_closed_60_days', "name" =>"Family Day Care Homes Closed - 60 Days", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 3],
            ["key"=>'child_day_care_licensing_program.family_day_care_homes_opened_1_year', "name" =>"Family Day Care Homes Opened - 1 Year", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 3],
            ["key"=>'child_day_care_licensing_program.family_day_care_homes_opened_60_days', "name" =>"Family Day Care Homes Opened - 60 Days", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 3],
            ["key"=>'child_day_care_licensing_program.family_day_care_homes_total_by_date_active', "name" =>"Family Day Care Homes Total by Date (Active)", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 3],
            ["key"=>'drug_control_pharmacy_pharmacists_etc.dealers_of_electronic_nicotine_delivery_systems_or_vapor_products', "name" =>"Dealers of Electronic Nicotine Delivery Systems or Vapor Products", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 4],
            ["key"=>'drug_control_pharmacy_pharmacists_etc.non_legend_drug_permits', "name" =>"Non-Legend Drug Permits", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 4],
            ["key"=>'drug_control_pharmacy_pharmacists_etc.non_resident_pharmacies', "name" =>"Non-Resident Pharmacies", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 4],
            ["key"=>'drug_control_pharmacy_pharmacists_etc.pharmacies', "name" =>"Pharmacies", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 4],
            ["key"=>'drug_control_pharmacy_pharmacists_etc.pharmacists', "name" =>"Pharmacists", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 4],
            ["key"=>'drug_control_pharmacy_pharmacists_etc.pharmacy_interns', "name" =>"Pharmacy Interns", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 4],
            ["key"=>'drug_control_pharmacy_pharmacists_etc.pharmacy_technicians', "name" =>"Pharmacy Technicians", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 4],
            ["key"=>'drug_control_pharmacy_pharmacists_etc.temporary_permit_to_practice_pharmacy', "name" =>"Temporary Permit to Practice Pharmacy", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 4],
            ["key"=>'emergency_medical_services.advanced_emergency_medical_technician', "name" =>"Advanced Emergency Medical Technician", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 5],
            ["key"=>'emergency_medical_services.certified_ems_organization', "name" =>"Certified EMS Organization", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 5],
            ["key"=>'emergency_medical_services.emergency_medical_responder', "name" =>"Emergency Medical Responder", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 5],
            ["key"=>'emergency_medical_services.emergency_medical_services_instructor', "name" =>"Emergency Medical Services Instructor", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 5],
            ["key"=>'emergency_medical_services.emergency_medical_technician', "name" =>"Emergency Medical Technician", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 5],
            ["key"=>'emergency_medical_services.first_responder_organization', "name" =>"First Responder Organization", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 5],
            ["key"=>'emergency_medical_services.licensed_ems_organization', "name" =>"Licensed EMS Organization", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 5],
            ["key"=>'emergency_medical_services.paramedic', "name" =>"Paramedic", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 5],
            ["key"=>'emergency_medical_services.supplemental_responder_ems_organization', "name" =>"Supplemental Responder EMS Organization", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 5],
            ["key"=>'healthcare_practitioners.acupuncturist', "name" =>"Acupuncturist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.advanced_practice_registered_nurse', "name" =>"Advanced Practice Registered Nurse", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.athletic_trainer', "name" =>"Athletic Trainer", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.audiologist', "name" =>"Audiologist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.chiropractor', "name" =>"Chiropractor", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.dental_anesthesia_conscious_sedation_permit', "name" =>"Dental Anesthesia/Conscious Sedation Permit", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.dental_conscious_sedation_permit', "name" =>"Dental Conscious Sedation Permit", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.dental_hygienist', "name" =>"Dental Hygienist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.dentist', "name" =>"Dentist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.dietitian_nutritionist', "name" =>"Dietitian/Nutritionist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.genetic_counselor', "name" =>"Genetic Counselor", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.hearing_instrument_specialist', "name" =>"Hearing Instrument Specialist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.homeopathic_physician', "name" =>"Homeopathic Physician", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.licensed_practical_nurse', "name" =>"Licensed Practical Nurse", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.massage_therapist', "name" =>"Massage Therapist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.naturopathic_physician', "name" =>"Naturopathic Physician", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.nurse_midwife', "name" =>"Nurse Midwife", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.occupational_therapist', "name" =>"Occupational Therapist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.occupational_therapist_assistant', "name" =>"Occupational Therapist Assistant", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.optician', "name" =>"Optician", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.optician_apprentice', "name" =>"Optician Apprentice", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.optometrist', "name" =>"Optometrist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.physical_therapist', "name" =>"Physical Therapist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.physical_therapist_assistant', "name" =>"Physical Therapist Assistant", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.physician_assistant', "name" =>"Physician Assistant", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.physician_surgeon_md_do', "name" =>"Physician/Surgeon - MD/DO", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.podiatrist', "name" =>"Podiatrist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.podiatrist_advanced_ankle_surgery_permit', "name" =>"Podiatrist-Advanced Ankle Surgery Permit", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.podiatrist_standard_ankle_surgery_permit', "name" =>"Podiatrist-Standard Ankle Surgery Permit", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.radiographer', "name" =>"Radiographer", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.registered_nurse', "name" =>"Registered Nurse", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.respiratory_care_practitioner', "name" =>"Respiratory Care Practitioner", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.speech_and_language_pathologist', "name" =>"Speech and Language Pathologist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'healthcare_practitioners.veterinarian', "name" =>"Veterinarian", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 6],
            ["key"=>'hemodialysis_centers.hemodialysis_centers', "name" =>"Hemodialysis Centers", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 7],
            ["key"=>'hospitals.childrens_hospitals', "name" =>"Childrens Hospitals", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 8],
            ["key"=>'hospitals.chronic_disease_hospitals', "name" =>"Chronic Disease Hospitals", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 8],
            ["key"=>'hospitals.general_hospitals', "name" =>"General Hospitals", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 8],
            ["key"=>'hospitals.hospitals_for_mentally_ill_persons', "name" =>"Hospitals for Mentally Ill Persons", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 8],
            ["key"=>'hospitals.maternity_hospitals', "name" =>"Maternity Hospitals", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 8],
            ["key"=>'infirmaries_clinics.family_planning_clinics', "name" =>"Family Planning Clinics", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 9],
            ["key"=>'infirmaries_clinics.infirmary_operated_by_an_educational_institution', "name" =>"Infirmary Operated by an Educational Institution", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 9],
            ["key"=>'infirmaries_clinics.outpatient_clinic', "name" =>"Outpatient Clinic", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 9],
            ["key"=>'infirmaries_clinics.wll_child_clinic', "name" =>"Wll Child Clinic", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 9],
            ["key"=>'long_term_care_assisted_living_facilities_home_health_care_hospice.assisted_living_service_agency', "name" =>"Assisted Living Service Agency", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 10],
            ["key"=>'long_term_care_assisted_living_facilities_home_health_care_hospice.chronic_and_convalescent_nursing_home', "name" =>"Chronic and Convalescent Nursing Home", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 10],
            ["key"=>'long_term_care_assisted_living_facilities_home_health_care_hospice.chronic_and_convalescent_nursing_homes_and_rest_homes_with_nursing_supervision', "name" =>"Chronic and Convalescent Nursing Homes and Rest Homes with Nursing Supervision", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 10],
            ["key"=>'long_term_care_assisted_living_facilities_home_health_care_hospice.home_health_care_agencies', "name" =>"Home Health Care Agencies", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 10],
            ["key"=>'long_term_care_assisted_living_facilities_home_health_care_hospice.homemaker_home_health_care', "name" =>"Homemaker/Home Health Care", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 10],
            ["key"=>'long_term_care_assisted_living_facilities_home_health_care_hospice.hospice_care', "name" =>"Hospice Care", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 10],
            ["key"=>'long_term_care_assisted_living_facilities_home_health_care_hospice.nursing_home_administrator', "name" =>"Nursing Home Administrator", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 10],
            ["key"=>'long_term_care_assisted_living_facilities_home_health_care_hospice.nursing_home_management_company', "name" =>"Nursing Home Management Company", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 10],
            ["key"=>'long_term_care_assisted_living_facilities_home_health_care_hospice.residential_care_facility', "name" =>"Residential Care Facility", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 10],
            ["key"=>'long_term_care_assisted_living_facilities_home_health_care_hospice.rest_homes_with_nursing_supervision', "name" =>"Rest Homes with Nursing Supervision", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 10],
            ["key"=>'medical_marijuana_producer_dispensary_facility_dispensary_etc.medical_marijuana_dispensary_facility_backer_license', "name" =>"Medical Marijuana Dispensary Facility Backer License", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 11],
            ["key"=>'medical_marijuana_producer_dispensary_facility_dispensary_etc.medical_marijuana_dispensary_facility_employee_license', "name" =>"Medical Marijuana Dispensary Facility Employee License", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 11],
            ["key"=>'medical_marijuana_producer_dispensary_facility_dispensary_etc.medical_marijuana_dispensary_facility_license', "name" =>"Medical Marijuana Dispensary Facility License", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 11],
            ["key"=>'medical_marijuana_producer_dispensary_facility_dispensary_etc.medical_marijuana_dispensary_license', "name" =>"Medical Marijuana Dispensary License", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 11],
            ["key"=>'medical_marijuana_producer_dispensary_facility_dispensary_etc.medical_marijuana_dispensary_technician_license', "name" =>"Medical Marijuana Dispensary Technician License", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 11],
            ["key"=>'medical_marijuana_producer_dispensary_facility_dispensary_etc.medical_marijuana_producer_backer_license', "name" =>"Medical Marijuana Producer Backer License", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 11],
            ["key"=>'medical_marijuana_producer_dispensary_facility_dispensary_etc.medical_marijuana_producer_employee_license', "name" =>"Medical Marijuana Producer Employee License", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 11],
            ["key"=>'medical_marijuana_producer_dispensary_facility_dispensary_etc.medical_marijuana_producer_license', "name" =>"Medical Marijuana Producer License", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 11],
            ["key"=>'mental_health_care.clinical_social_worker', "name" =>"Clinical Social Worker", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'mental_health_care.hospitals_for_mentally_ill_persons', "name" =>"Hospitals for Mentally Ill Persons", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'mental_health_care.marital_and_family_therapist', "name" =>"Marital and Family Therapist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'mental_health_care.master_s_level_social_worker', "name" =>"Master's Level Social Worker", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'mental_health_care.mental_health_community_residence', "name" =>"Mental Health Community Residence", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'mental_health_care.mental_health_day_treatement_facilities', "name" =>"Mental Health Day Treatement Facilities", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'mental_health_care.mental_health_intermedicate_treatment_facilities', "name" =>"Mental Health Intermedicate Treatment Facilities", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'mental_health_care.mental_health_residential_living_facilities', "name" =>"Mental Health Residential Living Facilities", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'mental_health_care.professional_counselor', "name" =>"Professional Counselor", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'mental_health_care.psychiratric_outpatient_clinics', "name" =>"Psychiratric Outpatient Clinics", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'mental_health_care.psychologist', "name" =>"Psychologist", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 12],
            ["key"=>'registered_sanitarians.registered_sanitarian', "name" =>"Registered Sanitarian", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 13],
            ["key"=>'substance_abuse_care.certified_alcohol_and_drug_counselor', "name" =>"Certified Alcohol and Drug Counselor", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 14],
            ["key"=>'substance_abuse_care.licensed_alcohol_and_drug_counselor', "name" =>"Licensed Alcohol and Drug Counselor", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 14],
            ["key"=>'substance_abuse_care.substance_abuse_care_facilites', "name" =>"Substance Abuse Care Facilites", "created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s"), "ct_license_categories_id" => 14]
        ]);
    }
}