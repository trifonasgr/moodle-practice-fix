<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * local practice's index page
 *
 * @package    local_practice
 * @copyright  2022 onwards WIDE Services  {@link https://www.wideservices.gr}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use local_practice\output\main;
require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/practice_form.php');


$url = new moodle_url('/local/practice/index.php');
$PAGE->set_url($url);
$context = context_system::instance();
$PAGE->set_context($context);

$PAGE->set_title(get_string('practicetitle','local_practice'));
$PAGE->set_heading(get_string('practicetitle', 'local_practice'));

$mform = new practice_form(null);
if ($fromform = $mform->get_data()) {
    $errors = []; // Stores which fields have a problem
    
    // Check for empty fields
    if (empty($fromform->firstname)) {
        $errors['firstname'] = 'Name is required';
    }
    if (empty($fromform->lastname)) {
        $errors['lastname'] = 'Last name is required';
    }
    if (empty($fromform->email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($fromform->email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'The email you entered is not valid.';
    }

    // If there are errors, we display messages and color fields
    if (!empty($errors)) {
        foreach ($errors as $field => $message) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var field = document.querySelector('[name=\"$field\"]');
                    if (field) {
                        field.style.border = '2px solid red'; 
                        field.setAttribute('placeholder', '$message'); 
                    }
                });
            </script>";
        }
        $mform->set_data($fromform); // Restores previous data
    } 
    // If everything is correct, it saves to the database
    else {
        $insertrecord = new stdClass();
        $insertrecord->firstname = $fromform->firstname;
        $insertrecord->lastname = $fromform->lastname;
        $insertrecord->email = $fromform->email;
        $insertrecord->timecreated = time() - 86400;
        $insertrecord->timemodified = time();

        $DB->insert_record('local_practice', $insertrecord);
        redirect(new moodle_url('/local/practice/index.php'));
    }
}

$indexview=new main();
echo $OUTPUT->header();
echo $OUTPUT->render($indexview);
$mform->display();
echo $OUTPUT->footer();




