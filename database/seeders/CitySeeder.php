<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     
        City::firstOrCreate(['name'=>'Karachi',],['lang_id'=>1,'sequence'=>1,'slug'=>'karachi','status'=>1]);
        City::firstOrCreate(['name'=>'Lahore',],['lang_id'=>1,'sequence'=>2,'slug'=>'lahore','status'=>1]);
        City::firstOrCreate(['name'=>'Faisalabad',],['lang_id'=>1,'sequence'=>5,'slug'=>'faisalabad','status'=>1]);
        City::firstOrCreate(['name'=>'Rawalpindi',],['lang_id'=>1,'sequence'=>6,'slug'=>'rawalpindi','status'=>1]);
        City::firstOrCreate(['name'=>'Gujranwala',],['lang_id'=>1,'sequence'=>7,'slug'=>'gujranwala','status'=>1]);
        City::firstOrCreate(['name'=>'Peshawar',],['lang_id'=>1,'sequence'=>8,'slug'=>'peshawar','status'=>1]);
        City::firstOrCreate(['name'=>'Multan',],['lang_id'=>1,'sequence'=>9,'slug'=>'multan','status'=>1]);
        City::firstOrCreate(['name'=>'Saidu Sharif',],['lang_id'=>1,'sequence'=>10,'slug'=>'saidu-sharif','status'=>1]);
        City::firstOrCreate(['name'=>'Hyderabad City',],['lang_id'=>1,'sequence'=>11,'slug'=>'hyderabad-city','status'=>1]);
        City::firstOrCreate(['name'=>'Islamabad',],['lang_id'=>1,'sequence'=>12,'slug'=>'islamabad','status'=>1]);
        City::firstOrCreate(['name'=>'Quetta',],['lang_id'=>1,'sequence'=>13,'slug'=>'quetta','status'=>1]);
        City::firstOrCreate(['name'=>'Bahawalpur',],['lang_id'=>1,'sequence'=>14,'slug'=>'bahawalpur','status'=>1]);
        City::firstOrCreate(['name'=>'Sargodha',],['lang_id'=>1,'sequence'=>15,'slug'=>'sargodha','status'=>1]);
        City::firstOrCreate(['name'=>'Sialkot City',],['lang_id'=>1,'sequence'=>16,'slug'=>'sialkot-city','status'=>1]);
        City::firstOrCreate(['name'=>'Sukkur',],['lang_id'=>1,'sequence'=>17,'slug'=>'sukkur','status'=>1]);
        City::firstOrCreate(['name'=>'Larkana',],['lang_id'=>1,'sequence'=>18,'slug'=>'larkana','status'=>1]);
        City::firstOrCreate(['name'=>'Chiniot',],['lang_id'=>1,'sequence'=>19,'slug'=>'chiniot','status'=>1]);
        City::firstOrCreate(['name'=>'Shekhupura',],['lang_id'=>1,'sequence'=>20,'slug'=>'shekhupura','status'=>1]);
        City::firstOrCreate(['name'=>'Jhang City',],['lang_id'=>1,'sequence'=>21,'slug'=>'jhang-city','status'=>1]);
        City::firstOrCreate(['name'=>'Dera Ghazi Khan',],['lang_id'=>1,'sequence'=>22,'slug'=>'dera-ghazi-khan','status'=>1]);
        City::firstOrCreate(['name'=>'Gujrat',],['lang_id'=>1,'sequence'=>23,'slug'=>'gujrat','status'=>1]);
        City::firstOrCreate(['name'=>'Rahimyar Khan',],['lang_id'=>1,'sequence'=>24,'slug'=>'rahimyar-khan','status'=>1]);
        City::firstOrCreate(['name'=>'Kasur',],['lang_id'=>1,'sequence'=>25,'slug'=>'kasur','status'=>1]);
        City::firstOrCreate(['name'=>'Mardan',],['lang_id'=>1,'sequence'=>26,'slug'=>'mardan','status'=>1]);
        City::firstOrCreate(['name'=>'Mingaora',],['lang_id'=>1,'sequence'=>27,'slug'=>'mingaora','status'=>1]);
        City::firstOrCreate(['name'=>'Nawabshah',],['lang_id'=>1,'sequence'=>28,'slug'=>'nawabshah','status'=>1]);
        City::firstOrCreate(['name'=>'Sahiwal',],['lang_id'=>1,'sequence'=>29,'slug'=>'sahiwal','status'=>1]);
        City::firstOrCreate(['name'=>'Mirpur Khas',],['lang_id'=>1,'sequence'=>30,'slug'=>'mirpur-khas','status'=>1]);
        City::firstOrCreate(['name'=>'Okara',],['lang_id'=>1,'sequence'=>31,'slug'=>'okara','status'=>1]);
        City::firstOrCreate(['name'=>'Mandi Burewala',],['lang_id'=>1,'sequence'=>32,'slug'=>'mandi-burewala','status'=>1]);
        City::firstOrCreate(['name'=>'Jacobabad',],['lang_id'=>1,'sequence'=>33,'slug'=>'jacobabad','status'=>1]);
        City::firstOrCreate(['name'=>'Saddiqabad',],['lang_id'=>1,'sequence'=>34,'slug'=>'saddiqabad','status'=>1]);
        City::firstOrCreate(['name'=>'Kohat',],['lang_id'=>1,'sequence'=>35,'slug'=>'kohat','status'=>1]);
        City::firstOrCreate(['name'=>'Muridke',],['lang_id'=>1,'sequence'=>36,'slug'=>'muridke','status'=>1]);
        City::firstOrCreate(['name'=>'Muzaffargarh',],['lang_id'=>1,'sequence'=>37,'slug'=>'muzaffargarh','status'=>1]);
        City::firstOrCreate(['name'=>'Khanpur',],['lang_id'=>1,'sequence'=>38,'slug'=>'khanpur','status'=>1]);
        City::firstOrCreate(['name'=>'Gojra',],['lang_id'=>1,'sequence'=>39,'slug'=>'gojra','status'=>1]);
        City::firstOrCreate(['name'=>'Mandi Bahauddin',],['lang_id'=>1,'sequence'=>40,'slug'=>'mandi-bahauddin','status'=>1]);
        City::firstOrCreate(['name'=>'Abbottabad',],['lang_id'=>1,'sequence'=>41,'slug'=>'abbottabad','status'=>1]);
        City::firstOrCreate(['name'=>'Turbat',],['lang_id'=>1,'sequence'=>42,'slug'=>'turbat','status'=>1]);
        City::firstOrCreate(['name'=>'Dadu',],['lang_id'=>1,'sequence'=>43,'slug'=>'dadu','status'=>1]);
        City::firstOrCreate(['name'=>'Bahawalnagar',],['lang_id'=>1,'sequence'=>44,'slug'=>'bahawalnagar','status'=>1]);
        City::firstOrCreate(['name'=>'Khuzdar',],['lang_id'=>1,'sequence'=>45,'slug'=>'khuzdar','status'=>1]);
        City::firstOrCreate(['name'=>'Pakpattan',],['lang_id'=>1,'sequence'=>46,'slug'=>'pakpattan','status'=>1]);
        City::firstOrCreate(['name'=>'Tando Allahyar',],['lang_id'=>1,'sequence'=>47,'slug'=>'tando-allahyar','status'=>1]);
        City::firstOrCreate(['name'=>'Ahmadpur East',],['lang_id'=>1,'sequence'=>48,'slug'=>'ahmadpur-east','status'=>1]);
        City::firstOrCreate(['name'=>'Vihari',],['lang_id'=>1,'sequence'=>49,'slug'=>'vihari','status'=>1]);
        City::firstOrCreate(['name'=>'Jaranwala',],['lang_id'=>1,'sequence'=>50,'slug'=>'jaranwala','status'=>1]);
        City::firstOrCreate(['name'=>'New Mirpur',],['lang_id'=>1,'sequence'=>51,'slug'=>'new-mirpur','status'=>1]);
        City::firstOrCreate(['name'=>'Kamalia',],['lang_id'=>1,'sequence'=>52,'slug'=>'kamalia','status'=>1]);
        City::firstOrCreate(['name'=>'Kot Addu',],['lang_id'=>1,'sequence'=>53,'slug'=>'kot-addu','status'=>1]);
        City::firstOrCreate(['name'=>'Nowshera',],['lang_id'=>1,'sequence'=>54,'slug'=>'nowshera','status'=>1]);
        City::firstOrCreate(['name'=>'Swabi',],['lang_id'=>1,'sequence'=>55,'slug'=>'swabi','status'=>1]);
        City::firstOrCreate(['name'=>'Khushab',],['lang_id'=>1,'sequence'=>56,'slug'=>'khushab','status'=>1]);
        City::firstOrCreate(['name'=>'Dera Ismail Khan',],['lang_id'=>1,'sequence'=>57,'slug'=>'dera-ismail-khan','status'=>1]);
        City::firstOrCreate(['name'=>'Chaman',],['lang_id'=>1,'sequence'=>58,'slug'=>'chaman','status'=>1]);
        City::firstOrCreate(['name'=>'Charsadda',],['lang_id'=>1,'sequence'=>59,'slug'=>'charsadda','status'=>1]);
        City::firstOrCreate(['name'=>'Kandhkot',],['lang_id'=>1,'sequence'=>60,'slug'=>'kandhkot','status'=>1]);
        City::firstOrCreate(['name'=>'Chishtian',],['lang_id'=>1,'sequence'=>61,'slug'=>'chishtian','status'=>1]);
        City::firstOrCreate(['name'=>'Hasilpur',],['lang_id'=>1,'sequence'=>62,'slug'=>'hasilpur','status'=>1]);
        City::firstOrCreate(['name'=>'Attock Khurd',],['lang_id'=>1,'sequence'=>63,'slug'=>'attock-khurd','status'=>1]);
        City::firstOrCreate(['name'=>'Muzaffarabad',],['lang_id'=>1,'sequence'=>64,'slug'=>'muzaffarabad','status'=>1]);
        City::firstOrCreate(['name'=>'Mianwali',],['lang_id'=>1,'sequence'=>65,'slug'=>'mianwali','status'=>1]);
        City::firstOrCreate(['name'=>'Jalalpur Jattan',],['lang_id'=>1,'sequence'=>66,'slug'=>'jalalpur-jattan','status'=>1]);
        City::firstOrCreate(['name'=>'Bhakkar',],['lang_id'=>1,'sequence'=>67,'slug'=>'bhakkar','status'=>1]);
        City::firstOrCreate(['name'=>'Zhob',],['lang_id'=>1,'sequence'=>68,'slug'=>'zhob','status'=>1]);
        City::firstOrCreate(['name'=>'Dipalpur',],['lang_id'=>1,'sequence'=>69,'slug'=>'dipalpur','status'=>1]);
        City::firstOrCreate(['name'=>'Kharian',],['lang_id'=>1,'sequence'=>70,'slug'=>'kharian','status'=>1]);
        City::firstOrCreate(['name'=>'Mian Channun',],['lang_id'=>1,'sequence'=>71,'slug'=>'mian-channun','status'=>1]);
        City::firstOrCreate(['name'=>'Bhalwal',],['lang_id'=>1,'sequence'=>72,'slug'=>'bhalwal','status'=>1]);
        City::firstOrCreate(['name'=>'Jamshoro',],['lang_id'=>1,'sequence'=>73,'slug'=>'jamshoro','status'=>1]);
        City::firstOrCreate(['name'=>'Pattoki',],['lang_id'=>1,'sequence'=>74,'slug'=>'pattoki','status'=>1]);
        City::firstOrCreate(['name'=>'Harunabad',],['lang_id'=>1,'sequence'=>75,'slug'=>'harunabad','status'=>1]);
        City::firstOrCreate(['name'=>'Kahror Pakka',],['lang_id'=>1,'sequence'=>76,'slug'=>'kahror-pakka','status'=>1]);
        City::firstOrCreate(['name'=>'Toba Tek Singh',],['lang_id'=>1,'sequence'=>77,'slug'=>'toba-tek-singh','status'=>1]);
        City::firstOrCreate(['name'=>'Samundri',],['lang_id'=>1,'sequence'=>78,'slug'=>'samundri','status'=>1]);
        City::firstOrCreate(['name'=>'Shakargarh',],['lang_id'=>1,'sequence'=>79,'slug'=>'shakargarh','status'=>1]);
        City::firstOrCreate(['name'=>'Sambrial',],['lang_id'=>1,'sequence'=>80,'slug'=>'sambrial','status'=>1]);
        City::firstOrCreate(['name'=>'Shujaabad',],['lang_id'=>1,'sequence'=>81,'slug'=>'shujaabad','status'=>1]);
        City::firstOrCreate(['name'=>'Hujra Shah Muqim',],['lang_id'=>1,'sequence'=>82,'slug'=>'hujra-shah-muqim','status'=>1]);
        City::firstOrCreate(['name'=>'Kabirwala',],['lang_id'=>1,'sequence'=>83,'slug'=>'kabirwala','status'=>1]);
        City::firstOrCreate(['name'=>'Mansehra',],['lang_id'=>1,'sequence'=>84,'slug'=>'mansehra','status'=>1]);
        City::firstOrCreate(['name'=>'Lala Musa',],['lang_id'=>1,'sequence'=>85,'slug'=>'lala-musa','status'=>1]);
        City::firstOrCreate(['name'=>'Chunian',],['lang_id'=>1,'sequence'=>86,'slug'=>'chunian','status'=>1]);
        City::firstOrCreate(['name'=>'Nankana Sahib',],['lang_id'=>1,'sequence'=>87,'slug'=>'nankana-sahib','status'=>1]);
        City::firstOrCreate(['name'=>'Bannu',],['lang_id'=>1,'sequence'=>88,'slug'=>'bannu','status'=>1]);
        City::firstOrCreate(['name'=>'Pasrur',],['lang_id'=>1,'sequence'=>89,'slug'=>'pasrur','status'=>1]);
        City::firstOrCreate(['name'=>'Timargara',],['lang_id'=>1,'sequence'=>90,'slug'=>'timargara','status'=>1]);
        City::firstOrCreate(['name'=>'Parachinar',],['lang_id'=>1,'sequence'=>91,'slug'=>'parachinar','status'=>1]);
        City::firstOrCreate(['name'=>'Chenab Nagar',],['lang_id'=>1,'sequence'=>92,'slug'=>'chenab-nagar','status'=>1]);
        City::firstOrCreate(['name'=>'Gwadar',],['lang_id'=>1,'sequence'=>93,'slug'=>'gwadar','status'=>1]);
        City::firstOrCreate(['name'=>'Abdul Hakim',],['lang_id'=>1,'sequence'=>94,'slug'=>'abdul-hakim','status'=>1]);
        City::firstOrCreate(['name'=>'Hassan Abdal',],['lang_id'=>1,'sequence'=>95,'slug'=>'hassan-abdal','status'=>1]);
        City::firstOrCreate(['name'=>'Tank',],['lang_id'=>1,'sequence'=>96,'slug'=>'tank','status'=>1]);
        City::firstOrCreate(['name'=>'Hangu',],['lang_id'=>1,'sequence'=>97,'slug'=>'hangu','status'=>1]);
        City::firstOrCreate(['name'=>'Risalpur Cantonment',],['lang_id'=>1,'sequence'=>98,'slug'=>'risalpur-cantonment','status'=>1]);
        City::firstOrCreate(['name'=>'Karak',],['lang_id'=>1,'sequence'=>99,'slug'=>'karak','status'=>1]);
        City::firstOrCreate(['name'=>'Kundian',],['lang_id'=>1,'sequence'=>100,'slug'=>'kundian','status'=>1]);
        City::firstOrCreate(['name'=>'Umarkot',],['lang_id'=>1,'sequence'=>101,'slug'=>'umarkot','status'=>1]);
        City::firstOrCreate(['name'=>'Chitral',],['lang_id'=>1,'sequence'=>102,'slug'=>'chitral','status'=>1]);
        City::firstOrCreate(['name'=>'Dainyor',],['lang_id'=>1,'sequence'=>103,'slug'=>'dainyor','status'=>1]);
        City::firstOrCreate(['name'=>'Kulachi',],['lang_id'=>1,'sequence'=>104,'slug'=>'kulachi','status'=>1]);
        City::firstOrCreate(['name'=>'Kalat',],['lang_id'=>1,'sequence'=>105,'slug'=>'kalat','status'=>1]);
        City::firstOrCreate(['name'=>'Kotli',],['lang_id'=>1,'sequence'=>106,'slug'=>'kotli','status'=>1]);
        City::firstOrCreate(['name'=>'Gilgit',],['lang_id'=>1,'sequence'=>107,'slug'=>'gilgit','status'=>1]);
        City::firstOrCreate(['name'=>'Narowal',],['lang_id'=>1,'sequence'=>108,'slug'=>'narowal','status'=>1]);
        City::firstOrCreate(['name'=>'Khairpur Mir’s',],['lang_id'=>1,'sequence'=>109,'slug'=>'khairpur-mir’s','status'=>1]);
        City::firstOrCreate(['name'=>'Khanewal',],['lang_id'=>1,'sequence'=>110,'slug'=>'khanewal','status'=>1]);
        City::firstOrCreate(['name'=>'Jhelum',],['lang_id'=>1,'sequence'=>111,'slug'=>'jhelum','status'=>1]);
        City::firstOrCreate(['name'=>'Haripur',],['lang_id'=>1,'sequence'=>112,'slug'=>'haripur','status'=>1]);
        City::firstOrCreate(['name'=>'Shikarpur',],['lang_id'=>1,'sequence'=>113,'slug'=>'shikarpur','status'=>1]);
        City::firstOrCreate(['name'=>'Rawala Kot',],['lang_id'=>1,'sequence'=>114,'slug'=>'rawala-kot','status'=>1]);
        City::firstOrCreate(['name'=>'Hafizabad',],['lang_id'=>1,'sequence'=>115,'slug'=>'hafizabad','status'=>1]);
        City::firstOrCreate(['name'=>'Lodhran',],['lang_id'=>1,'sequence'=>116,'slug'=>'lodhran','status'=>1]);
        City::firstOrCreate(['name'=>'Malakand',],['lang_id'=>1,'sequence'=>117,'slug'=>'malakand','status'=>1]);
        City::firstOrCreate(['name'=>'Attock City',],['lang_id'=>1,'sequence'=>118,'slug'=>'attock-city','status'=>1]);
        City::firstOrCreate(['name'=>'Batgram',],['lang_id'=>1,'sequence'=>119,'slug'=>'batgram','status'=>1]);
        City::firstOrCreate(['name'=>'Matiari',],['lang_id'=>1,'sequence'=>120,'slug'=>'matiari','status'=>1]);
        City::firstOrCreate(['name'=>'Ghotki',],['lang_id'=>1,'sequence'=>121,'slug'=>'ghotki','status'=>1]);
        City::firstOrCreate(['name'=>'Naushahro Firoz',],['lang_id'=>1,'sequence'=>122,'slug'=>'naushahro-firoz','status'=>1]);
        City::firstOrCreate(['name'=>'Alpurai',],['lang_id'=>1,'sequence'=>123,'slug'=>'alpurai','status'=>1]);
        City::firstOrCreate(['name'=>'Bagh',],['lang_id'=>1,'sequence'=>124,'slug'=>'bagh','status'=>1]);
        City::firstOrCreate(['name'=>'Daggar',],['lang_id'=>1,'sequence'=>125,'slug'=>'daggar','status'=>1]);
        City::firstOrCreate(['name'=>'Leiah',],['lang_id'=>1,'sequence'=>126,'slug'=>'leiah','status'=>1]);
        City::firstOrCreate(['name'=>'Tando Muhammad Khan',],['lang_id'=>1,'sequence'=>127,'slug'=>'tando-muhammad-khan','status'=>1]);
        City::firstOrCreate(['name'=>'Chakwal',],['lang_id'=>1,'sequence'=>128,'slug'=>'chakwal','status'=>1]);
        City::firstOrCreate(['name'=>'Badin',],['lang_id'=>1,'sequence'=>129,'slug'=>'badin','status'=>1]);
        City::firstOrCreate(['name'=>'Lakki',],['lang_id'=>1,'sequence'=>130,'slug'=>'lakki','status'=>1]);
        City::firstOrCreate(['name'=>'Rajanpur',],['lang_id'=>1,'sequence'=>131,'slug'=>'rajanpur','status'=>1]);
        City::firstOrCreate(['name'=>'Dera Allahyar',],['lang_id'=>1,'sequence'=>132,'slug'=>'dera-allahyar','status'=>1]);
        City::firstOrCreate(['name'=>'Shahdad Kot',],['lang_id'=>1,'sequence'=>133,'slug'=>'shahdad-kot','status'=>1]);
        City::firstOrCreate(['name'=>'Pishin',],['lang_id'=>1,'sequence'=>134,'slug'=>'pishin','status'=>1]);
        City::firstOrCreate(['name'=>'Sanghar',],['lang_id'=>1,'sequence'=>135,'slug'=>'sanghar','status'=>1]);
        City::firstOrCreate(['name'=>'Upper Dir',],['lang_id'=>1,'sequence'=>136,'slug'=>'upper-dir','status'=>1]);
        City::firstOrCreate(['name'=>'Thatta',],['lang_id'=>1,'sequence'=>137,'slug'=>'thatta','status'=>1]);
        City::firstOrCreate(['name'=>'Dera Murad Jamali',],['lang_id'=>1,'sequence'=>138,'slug'=>'dera-murad-jamali','status'=>1]);
        City::firstOrCreate(['name'=>'Kohlu',],['lang_id'=>1,'sequence'=>139,'slug'=>'kohlu','status'=>1]);
        City::firstOrCreate(['name'=>'Mastung',],['lang_id'=>1,'sequence'=>140,'slug'=>'mastung','status'=>1]);
        City::firstOrCreate(['name'=>'Dasu',],['lang_id'=>1,'sequence'=>141,'slug'=>'dasu','status'=>1]);
        City::firstOrCreate(['name'=>'Athmuqam',],['lang_id'=>1,'sequence'=>142,'slug'=>'athmuqam','status'=>1]);
        City::firstOrCreate(['name'=>'Loralai',],['lang_id'=>1,'sequence'=>143,'slug'=>'loralai','status'=>1]);
        City::firstOrCreate(['name'=>'Barkhan',],['lang_id'=>1,'sequence'=>144,'slug'=>'barkhan','status'=>1]);
        City::firstOrCreate(['name'=>'Musa Khel Bazar',],['lang_id'=>1,'sequence'=>145,'slug'=>'musa-khel-bazar','status'=>1]);
        City::firstOrCreate(['name'=>'Ziarat',],['lang_id'=>1,'sequence'=>146,'slug'=>'ziarat','status'=>1]);
        City::firstOrCreate(['name'=>'Gandava',],['lang_id'=>1,'sequence'=>147,'slug'=>'gandava','status'=>1]);
        City::firstOrCreate(['name'=>'Sibi',],['lang_id'=>1,'sequence'=>148,'slug'=>'sibi','status'=>1]);
        City::firstOrCreate(['name'=>'Dera Bugti',],['lang_id'=>1,'sequence'=>149,'slug'=>'dera-bugti','status'=>1]);
        City::firstOrCreate(['name'=>'Eidgah',],['lang_id'=>1,'sequence'=>150,'slug'=>'eidgah','status'=>1]);
        City::firstOrCreate(['name'=>'Uthal',],['lang_id'=>1,'sequence'=>151,'slug'=>'uthal','status'=>1]);
        City::firstOrCreate(['name'=>'Khuzdar',],['lang_id'=>1,'sequence'=>152,'slug'=>'khuzdar','status'=>1]);
        City::firstOrCreate(['name'=>'Chilas',],['lang_id'=>1,'sequence'=>153,'slug'=>'chilas','status'=>1]);
        City::firstOrCreate(['name'=>'Panjgur',],['lang_id'=>1,'sequence'=>154,'slug'=>'panjgur','status'=>1]);
        City::firstOrCreate(['name'=>'Gakuch',],['lang_id'=>1,'sequence'=>155,'slug'=>'gakuch','status'=>1]);
        City::firstOrCreate(['name'=>'Qila Saifullah',],['lang_id'=>1,'sequence'=>156,'slug'=>'qila-saifullah','status'=>1]);
        City::firstOrCreate(['name'=>'Kharan',],['lang_id'=>1,'sequence'=>157,'slug'=>'kharan','status'=>1]);
        City::firstOrCreate(['name'=>'Aliabad',],['lang_id'=>1,'sequence'=>158,'slug'=>'aliabad','status'=>1]);
        City::firstOrCreate(['name'=>'Awaran',],['lang_id'=>1,'sequence'=>159,'slug'=>'awaran','status'=>1]);
        City::firstOrCreate(['name'=>'Dalbandin',],['lang_id'=>1,'sequence'=>160,'slug'=>'dalbandin','status'=>1]);

        
    }
}
