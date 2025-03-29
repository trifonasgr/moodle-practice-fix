Βήμα 1: Εγκατάσταση Moodle σε υπολογιστή Windows
-	από τα αρχεία της Module Practice ελέγχουμε το αρχείο version.php όπου μπορούμε να δούμε την version του Moodle που λειτουργεί η Module 
•	Έκδοση plugin: 2023051200
•	Απαιτεί Moodle έκδοση: 3.7 (2019051700) ή νεότερη
•	Στοιχείο (component): local_practice
•	Κατάσταση: Σταθερό (Stable)
•	Release: 1

Για υπολογιστή με Windows Κατεβάζουμε από το επίσημο site της Moodle το LMS4 και το εγκαθιστούμε στον υπολογιστή μας


Βήμα 2: Εγκατάσταση Practice Module στο Moodle
-	Συνδεόμαστε στο admin panel από η διεύθυνση https://localhost/admin/ 
-	Από το οριζόντιο μενού επιλέγουμε Plugins  Install plugins
-	Στο ZIP package επιλέγουμε το αρχείο Practice.zip  και το ανεβάζουμε στο server
-	Κάνουμε κλικ στο install plugin from the ZIP file
-	Κάνουμε κλικ στο Continue στο Debugging output
-	Κάνουμε κλικ στο Continue στο Server checks.
(εφόσον περάσει όλους τους ελέγχους εμφανίζει το μήνυμα Your server environment meets all minimum requirements.)
-	Κάνουμε κλικ στο Update Moodle Database για ενημερωθεί η βάση δεδομένων του Moodle
-	Upgrading to new version: Κάνουμε κλικ στο Continue για να ολοκληρωθεί η διαδικασία
Από το μενού πάμε plugins -> Plugins overview για να δούμε αν έχει εγκατασταθεί η module Practice

Βήμα 3: Έλεγχος Frontend Φόρμας
-	από την διεύθυνση https://localhost/local/practice/ βλέπουμε την φόρμα

Βήμα 4: Διόρθωση Σφαλμάτων (Errors & Fixes)
1. Σφάλμα "Error writing to database"
Αιτία: Το timemodified δεν είχε προεπιλεγμένη τιμή.
Διόρθωση:
Άνοιγμα local/practice/index.php
Προσθήκη:
$insertrecord->timemodified = time();

2. Λάθος αποθήκευση Lastname
Αιτία: Στη γραμμή 44 του index.php, το lastname έπαιρνε την τιμή του firstname.
Διόρθωση:
Αντικατάσταση
$insertrecord->lastname = $fromform->firstname;
με
$insertrecord->lastname = $fromform->lastname;

3. Διόρθωση main.mustache
Αιτία: Στη γραμμή 14 του templates/main.mustache, το firstname εμφανιζόταν δύο φορές.
Διόρθωση:
Αλλαγή του δεύτερου firstname σε lastname.

4. Το timecreated δεν εμφανίζεται
Αιτία: Το timecreated δεν επιστρεφόταν από τη βάση δεδομένων.
Διόρθωση:
Άνοιγμα ./classes/output/main.php
Τροποποίηση της γραμμής 55:
$data[]=array('firstname'=>$record->firstname,'lastname'=>$record->lastname,'email'=>$record->email);
σε:
$data[]=array('firstname'=>$record->firstname,'lastname'=>$record->lastname,'email'=>$record->email,'timecreated'=>$timecreated);

5. Υποχρεωτικά πεδία & Έλεγχος Email
Αλλαγές:
Όλα τα πεδία (firstname, lastname, email) έγιναν υποχρεωτικά.
Προστέθηκε έλεγχος για έγκυρη διεύθυνση email.
Αν κάποιο πεδίο λείπει, γίνεται επισήμανση με κόκκινο χρώμα.
Διόρθωση:
Αντικατάσταση του κώδικα από τη γραμμή 38 και κάτω στο index.php.
