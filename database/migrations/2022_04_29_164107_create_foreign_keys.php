<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up ()
    {
        Schema::table('notifications', function ($table)
        {
            $table->foreign('id_account')->references('id')->on('accounts');
        });

        Schema::table('accounts', function ($table)
        {
            $table->foreign('id_role')->references('id')->on('roles');
        });

        Schema::table('academic_years', function ($table)
        {
            $table->foreign('id_training_type')->references('id')->on('training_types');
        });

        Schema::table('academic_year_major', function ($table)
        {
            $table->foreign('id_academic_year')->references('id')->on('academic_years');
            $table->foreign('id_major')->references('id')->on('majors');
            $table->foreign('id_curriculum')->references('id')->on('curriculums');
        });

        Schema::table('account_tag', function ($table)
        {
            $table->foreign('id_account')->references('id')->on('accounts');
            $table->foreign('id_tag')->references('id')->on('tags');
        });

        Schema::table('classes', function ($table)
        {
            $table->foreign('id_academic_year')->references('id')->on('academic_years');
            $table->foreign('id_faculty')->references('id')->on('faculties');
        });

        Schema::table('curriculum_module', function ($table)
        {
            $table->foreign('id_curriculum')->references('id')->on('curriculums');
            $table->foreign('id_module')->references('id')->on('modules');
        });

        Schema::table('departments', function ($table)
        {
            $table->foreign('id_faculty')->references('id')->on('faculties');
        });

        Schema::table('exam_schedules', function ($table)
        {
            $table->foreign('id')->references('id')->on('module_classes');
            $table->foreign('id_room')->references('id')->on('rooms');
        });

        Schema::table('exam_schedule_teacher', function ($table)
        {
            $table->foreign('id_exam_schedule')->references('id')->on('exam_schedules');
            $table->foreign('id_teacher')->references('id')->on('teachers');
        });


        Schema::table('feedback', function ($table)
        {
            $table->foreign('id_account')->references('id')->on('accounts');
        });


        Schema::table('fixed_schedules', function ($table)
        {
            $table->foreign('id_schedule')->references('id')->on('schedules');
            $table->foreign('old_id_room')->references('id')->on('rooms');
            $table->foreign('new_id_room')->references('id')->on('rooms');
        });


        Schema::table('majors', function ($table)
        {
            $table->foreign('id_faculty')->references('id')->on('faculties');
        });


        Schema::table('modules', function ($table)
        {
            $table->foreign('id_department')->references('id')->on('departments');
        });


        Schema::table('module_classes', function ($table)
        {
            $table->foreign('id_study_session')->references('id')->on('study_sessions');
            $table->foreign('id_module')->references('id')->on('modules');
            $table->foreign('id_teacher')->references('id')->on('teachers');
        });

        Schema::table('account_notification', function ($table)
        {
            $table->foreign('id_account')->references('id')->on('accounts');
            $table->foreign('id_notification')->references('id')->on('notifications');
        });

        Schema::table('notification_tag', function ($table)
        {
            $table->foreign('id_notification')->references('id')->on('notifications');
            $table->foreign('id_tag')->references('id')->on('tags');
        });

        Schema::table('module_class_student', function ($table)
        {
            $table->foreign('id_module_class')->references('id')->on('module_classes');
            $table->foreign('id_student')->references('id')->on('students');
        });


        Schema::table('permission_role', function ($table)
        {
            $table->foreign('id_permission')->references('id')->on('permissions');
            $table->foreign('id_role')->references('id')->on('roles');
        });

        Schema::table('schedules', function ($table)
        {
            $table->foreign('id_module_class')->references('id')->on('module_classes');
            $table->foreign('id_room')->references('id')->on('rooms');
        });

        Schema::table('students', function ($table)
        {
            $table->foreign('id_class')->references('id')->on('classes');
        });

        Schema::table('study_sessions', function ($table)
        {
            $table->foreign('id_term')->references('id')->on('terms');
        });

        Schema::table('terms', function ($table)
        {
            $table->foreign('id_school_year')->references('id')->on('school_years');
        });

        Schema::table('teachers', function ($table)
        {
            $table->foreign('id_department')->references('id')->on('teachers');
        });

        Schema::table('third_party_tokens', function ($table)
        {
            $table->foreign('id_account')->references('id')->on('accounts');
        });

        Schema::table('data_version_students', function ($table)
        {
            $table->foreign('id')->on('students')->references('id');
        });

        Schema::table('data_version_teachers', function ($table)
        {
            $table->foreign('id')->on('teachers')->references('id');
        });

        Schema::table('fcm_registration_tokens', function ($table)
        {
            $table->foreign('id_account')->on('accounts')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('foreign_keys');
    }
}
