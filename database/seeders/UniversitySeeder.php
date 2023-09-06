<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        University::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                "name" => "Abasyn University",
                "sector" => "Private",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Abbottabad University of Science and Technology (AUST)",
                "sector" => "Public",
                "city" => "Abbottabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Abdul Wali Khan University",
                "sector" => "Public",
                "city" => "Mardan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Aga Khan University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Air University",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Al-Hamd Islamic University",
                "sector" => "Private",
                "city" => "Quetta",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Ali Institute of Education",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Allama Iqbal Open University",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Bacha Khan University",
                "sector" => "Public",
                "city" => "Charsada",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Bahauddin Zakariya University",
                "sector" => "Public",
                "city" => "Multan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Bahria University",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Balochistan University of Engineering & Technology",
                "sector" => "Public",
                "city" => "Khuzdar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Balochistan University of Information Technology & Management Sciences",
                "sector" => "Public",
                "city" => "Quetta",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Baqai Medical University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Barret Hodgson University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Beaconhouse National University",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Benazir Bhutto Shaheed University Lyari",
                "sector" => "Public",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Benazir Bhutto Shaheed University of Technology & Skill Development, Khairpur Mirs",
                "sector" => "Public",
                "city" => "Khairpur",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Capital University of Science & Technology",
                "sector" => "Private",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "CECOS University of Information Technology & Emerging Sciences",
                "sector" => "Private",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "City University of Science and Information Technology",
                "sector" => "Private",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Commecs Institute of Business & Emerging Sciences",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "COMSATS Institute of Information Technology",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Dadabhoy Institute of Higher Education",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Dawood University of Engineering & Technology",
                "sector" => "Public",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "DHA Suffa University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "DOW University of Health Sciences",
                "sector" => "Public",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Fatima Jinnah Medical University, Lahore",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Fatima Jinnah Women University",
                "sector" => "Public",
                "city" => "Rawalpindi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Federal Urdu University of Arts, Sciences & Technology",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Forman Christian College",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Foundation University",
                "sector" => "Private",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Gambat Institute of Medical Sciences",
                "sector" => "Public",
                "city" => "Khairpur",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Gandhara University",
                "sector" => "Private",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Ghazi University",
                "sector" => "Public",
                "city" => "Dera Ghazi Khan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Ghulam Ishaq Khan Institute of Engineering Sciences & Technology",
                "sector" => "Private",
                "city" => "Swabi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "GIFT University",
                "sector" => "Private",
                "city" => "Gujranwala",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Global Institute, Lahore [HEC-NOC SUSPENDED] (Admission & Attestation stopped by HEC from 2016)",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Gomal University",
                "sector" => "Public",
                "city" => "Dera Ismail Khan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Government College for Women University",
                "sector" => "Public",
                "city" => "Faisalabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Government College for Women University, Sialkot",
                "sector" => "Public",
                "city" => "Sialkot",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Government College University",
                "sector" => "Public",
                "city" => "Faisalabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Government College University, Lahore",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Government Sadiq College Women University",
                "sector" => "Public",
                "city" => "Bahawal Pur",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Greenwich University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Habib University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Hajvery University",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Hamdard University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Hazara University",
                "sector" => "Public",
                "city" => "Mansehra",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "HITEC University",
                "sector" => "Private",
                "city" => "Rawalpindi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Imperial College of Business Studies",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Indus University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Indus Valley School of Art & Architecture",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Information Technology University of Punjab",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Institute of Arts & Culture",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Institute of Business & Technology (Admissions Banned)",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Institute of Business Administration",
                "sector" => "Public",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Institute of Business Management",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Institute of Management Sciences",
                "sector" => "Public",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Institute of Management Sciences",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Institute of Southern Punjab",
                "sector" => "Private",
                "city" => "Multan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Institute of Space Technology",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "International Islamic University",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Iqra National University",
                "sector" => "Private",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Iqra University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Islamia College University",
                "sector" => "Public",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Islamia University",
                "sector" => "Public",
                "city" => "Bahawal Pur",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Isra University",
                "sector" => "Private",
                "city" => "Hyderabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Jinnah Sindh Medical University",
                "sector" => "Public",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Jinnah University for Women",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Karachi Institute of Economics & Technology",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Karachi School for Business & Leadership",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Karakurum International University",
                "sector" => "Public",
                "city" => "Gilgit",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "KASB Institute of Technology",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Khawaja Freed University of Engineering & Information Technology",
                "sector" => "Public",
                "city" => "Rahim Yar Khan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Khushal Khan Khattak University",
                "sector" => "Public",
                "city" => "Karak",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Khyber Medical University",
                "sector" => "Public",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Khyber Pakhtunkhwa Agricultural University",
                "sector" => "Public",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "King Edward Medical University",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Kinnaird College for Women",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Kohat University of Science and Technology",
                "sector" => "Public",
                "city" => "Kohat",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Kohsar University, Murree",
                "sector" => "Public",
                "city" => "Rawalpindi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Lahore College for Women University",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Lahore Garrison University",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Lahore Leads University",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Lahore School of Economics",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Lahore University of Management Sciences",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Lasbela University of Agriculture, Water & Marine Sciences",
                "sector" => "Public",
                "city" => "Lasbella",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Liaquat University of Medical & Health Sciences",
                "sector" => "Public",
                "city" => "Jam Shoro",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Mehran University of Engineering & Technology",
                "sector" => "Public",
                "city" => "Jam Shoro",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Minhaj University",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Mir Chakar Khan Rind University of Technology, DG Khan",
                "sector" => "Public",
                "city" => "DG Khan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Mirpur University of Science & Technology",
                "sector" => "Public",
                "city" => "Mirpur",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Mishtar Medical University Multan",
                "sector" => "Public",
                "city" => "Multan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Mohammad Ali Jinnah University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Mohi-ud-Din Islamic University",
                "sector" => "Private",
                "city" => "Nerian Sharif",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Muhammad Nawaz Shareef University of Agriculture",
                "sector" => "Public",
                "city" => "Multan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Muhammad Nawaz Sharif University of Engineering & Technology",
                "sector" => "Public",
                "city" => "Multan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Muslim Youth University",
                "sector" => "Private",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Namal Institute, Mianwali",
                "sector" => "Private",
                "city" => "Mianwali",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "National College of Arts",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "National College of Business Administration & Economics",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "National Defense University",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "National Textile University",
                "sector" => "Public",
                "city" => "Faisalabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "National University of Computer & Emerging Sciences",
                "sector" => "Private",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "National University of Medical Sciences",
                "sector" => "Public",
                "city" => "Rawalpindi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "National University of Modern Languages",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "National University of Sciences & Technology",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Nazeer Hussain University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "NED University of Engineering & Technology",
                "sector" => "Public",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Newport Institute of Communications & Economics",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "NFC Institute of Engineering & Technology",
                "sector" => "Public",
                "city" => "Multan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Northern University",
                "sector" => "Private",
                "city" => "Nowshehra",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Nur International University",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Pakistan Institute of Development Economics (PIDE)",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Pakistan Institute of Engineering & Applied Sciences",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Pakistan Institute of Fashion & Design",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Pakistan Military Academy",
                "sector" => "Public",
                "city" => "Abbottabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Pakistan Naval Academy",
                "sector" => "Public",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Peoples University of Medical & Health Sciences for Women",
                "sector" => "Public",
                "city" => "Nawabshah",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Pir Mehr Ali Shah Arid Agriculture University",
                "sector" => "Public",
                "city" => "Rawalpindi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Preston Institute of Management, Science & Technology (Banned for admissions w.e.f 10-06-2017)",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Preston University",
                "sector" => "Private",
                "city" => "Kohat",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Preston University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Punjab Tianjin University of Technology",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Qarshi University",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Quaid-e-Awam University of Engineering, Sciences & Technology",
                "sector" => "Public",
                "city" => "Nawabshah",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Quaid-i-Azam University",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Qurtaba University of Science & Information Technology",
                "sector" => "Private",
                "city" => "Dera Ismail Khan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Rawalpindi Medical University",
                "sector" => "Public",
                "city" => "Rawalpindi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Riphah International University",
                "sector" => "Private",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Sardar Bahadur Khan Women University",
                "sector" => "Public",
                "city" => "Quetta",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Sarhad University of Science & Information Technology",
                "sector" => "Private",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shah Abdul Latif University",
                "sector" => "Public",
                "city" => "Khairpur",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shaheed Benazir Bhutto City University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shaheed Benazir Bhutto Dewan University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shaheed Benazir Bhutto University",
                "sector" => "Public",
                "city" => "Upper Dir",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shaheed Benazir Bhutto University of Veterinary & Animal Sciences",
                "sector" => "Public",
                "city" => "Nawabshah",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shaheed Benazir Bhutto University, Shaheed Benazirabad",
                "sector" => "Public",
                "city" => "Nawabshah",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shaheed Benazir Bhutto Women University",
                "sector" => "Public",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shaheed Mohtarma Benazir Bhutto Medical University",
                "sector" => "Public",
                "city" => "Larkana",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shaheed Zulfikar Ali Bhutto Institute of Science & Technology",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shaheed Zulfiqar Ali Bhutto Medical University",
                "sector" => "Public",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shaheed Zulfiqar Ali Bhutto University of Law",
                "sector" => "Public",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shifa Tameer-e-Millat University",
                "sector" => "Private",
                "city" => "Islamabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Shuhada-e-Army Public School University of Technology, Nowshera",
                "sector" => "Public",
                "city" => "Nowshehra",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Sindh Agriculture University, Tandojam",
                "sector" => "Public",
                "city" => "Hyderabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Sindh Institute of Management & Technology (Admissions Banned)",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Sindh Institute of Medical Sciences",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Sindh Madresatul Islam University",
                "sector" => "Public",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Sir Syed University of Engineering & Technology",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Sukkur Institute of Business Administration",
                "sector" => "Public",
                "city" => "Sukkur",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Textile Institute of Pakistan",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "The Green International University",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "The Superior College",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "The University of Faisalabad",
                "sector" => "Private",
                "city" => "Faisalabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "The Women University",
                "sector" => "Public",
                "city" => "Multan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Times Institute",
                "sector" => "Private",
                "city" => "Multan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Agriculture",
                "sector" => "Public",
                "city" => "Faisalabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Azad Jammu & Kashmir",
                "sector" => "Public",
                "city" => "Muzaffarabad",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Balochistan",
                "sector" => "Public",
                "city" => "Quetta",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Central Punjab",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Education",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Engineering & Technology",
                "sector" => "Public",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Engineering & Technology",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Engineering & Technology, Taxila",
                "sector" => "Public",
                "city" => "Rawalpindi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of FATA",
                "sector" => "Public",
                "city" => "Kohat",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Gujrat",
                "sector" => "Public",
                "city" => "Gujrat",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Haripur",
                "sector" => "Public",
                "city" => "Haripur",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Health Sciences",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Karachi",
                "sector" => "Public",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Kotli Azad Jammu and Kashmir",
                "sector" => "Public",
                "city" => "Kotli",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Lahore",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Loralai",
                "sector" => "Public",
                "city" => "Loralai",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Malakand",
                "sector" => "Public",
                "city" => "Malakand",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Management & Technology",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Peshawar",
                "sector" => "Public",
                "city" => "Peshawar",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Poonch",
                "sector" => "Public",
                "city" => "Poonch",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Punjab",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Sargodha",
                "sector" => "Public",
                "city" => "Sargodha",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Science & Technology",
                "sector" => "Public",
                "city" => "Bannu",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Sindh",
                "sector" => "Public",
                "city" => "Jam Shoro",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of South Asia",
                "sector" => "Private",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Swabi",
                "sector" => "Public",
                "city" => "Swabi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Swat",
                "sector" => "Public",
                "city" => "Swat",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Turbat",
                "sector" => "Public",
                "city" => "Turbat",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Veterinary & Animal Sciences",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "University of Wah",
                "sector" => "Private",
                "city" => "Rawalpindi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Virtual University of Pakistan",
                "sector" => "Public",
                "city" => "Lahore",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Women University Mardan",
                "sector" => "Public",
                "city" => "Mardan",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Women University of Azad Jammu & Kashmir",
                "sector" => "Public",
                "city" => "Bagh",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Women University, Swabi",
                "sector" => "Public",
                "city" => "Swabi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "name" => "Zia-ud-Din University",
                "sector" => "Private",
                "city" => "Karachi",
                "status" => true,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];
        University::insert($data);
    }
}
