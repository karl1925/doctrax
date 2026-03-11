<?php

namespace Database\Seeders;

use App\Models\{User, Setting};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        Storage::disk('public')->deleteDirectory('avatars');
        Storage::disk('public')->deleteDirectory('attachments');
        Storage::disk('public')->deleteDirectory('requests');
        Storage::disk('public')->deleteDirectory('documents');

        Storage::disk('public')->makeDirectory('avatars');
        Storage::disk('public')->makeDirectory('attachments');
        Storage::disk('public')->makeDirectory('requests');
        Storage::disk('public')->makeDirectory('documents');

        $team = [
            ["name" => "ENGR. PINKY T. JIMENEZ", "email" => "pinky.jimenez@dict.gov.ph"],
            ["name" => "Engr. Magdalena D. Gomez", "email" => "magie.gomez@dict.gov.ph"],
            ["name" => "Mina Flor Villafuerte", "email" => "mina.villafuerte@dict.gov.ph"],
            ["name" => "Ronald S. Bariuan", "email" => "ronie.bariuan@dict.gov.ph"],
            ["name" => "Johanna Tulauan", "email" => "johanna.tulauan@dict.gov.ph"],
            ["name" => "Edison Agaoid", "email" => "edison.agaoid@dict.gov.ph"],
            ["name" => "Jemar Jay Del Rosario", "email" => "jemar.delrosario@dict.gov.ph"],
            ["name" => "Johny Batang", "email" => "johny.batang@dict.gov.ph"],
            ["name" => "Jayfer Ammasi", "email" => "jayfer.ammasi@dict.gov.ph"],
            ["name" => "Samantha Dawideo", "email" => "samantha.dawideo@dict.gov.ph"],
            ["name" => "Janet", "email" => "janet.catinoy@dict.gov.ph"],
            ["name" => "Leonor J. Tumaliuan", "email" => "leonor.tumaliuan@dict.gov.ph"],
            ["name" => "Karl Steven A. Maddela", "email" => "karlsteven.maddela@dict.gov.ph"],
            ["name" => "Ma. Elijah Pilotin", "email" => "maelijah.pilotin@dict.gov.ph"],
            ["name" => "Mohamadnor Usman", "email" => "mohamadnor.usman@dict.gov.ph"],
            ["name" => "Christian Lazaro Caldez", "email" => "christian.caldez@dict.gov.ph"],
            ["name" => "Leo Jay Alilam", "email" => "leo.alilam@dict.gov.ph"],
            ["name" => "Glenard Martin", "email" => "glenard.martin@dict.gov.ph"],
            ["name" => "Herlyn Kaye Natividad", "email" => "herlynkaye.natividad@dict.gov.ph"],
            ["name" => "Jonathan Del Rosario", "email" => "jonathan.delrosario@dict.gov.ph"],
            ["name" => "RITO G. BANAN", "email" => "rito.banan@dict.gov.ph"],
            ["name" => "Kim", "email" => "kimberly.calayan@dict.gov.ph"],
            ["name" => "Shereen Tabug", "email" => "shereen.tabug@dict.gov.ph"],
            ["name" => "Aries Anthony Guim", "email" => "aries.guim@dict.gov.ph"],
            ["name" => "Alison Ahmad Abbas", "email" => "alison.abbas@dict.gov.ph"],
            ["name" => "Shane", "email" => "cyrill.cepeda@dict.gov.ph"],
            ["name" => "Lot-Lot Acera", "email" => "lotlot.acera@dict.gov.ph"],
            ["name" => "Jeanne Shannon", "email" => "jeanne.garcia@dict.gov.ph"],
            ["name" => "EXEN CLARO", "email" => "exen.claro@dict.gov.ph"],
            ["name" => "Maylanie Maggay", "email" => "maylanie.maggay@dict.gov.ph"],
            ["name" => "Czyione Dayl Mendoza", "email" => "cyzione.mendoza@dict.gov.ph"],
            ["name" => "Eugene Pabro", "email" => "eugene.pabro@dict.gov.ph"],
            ["name" => "Michael Angelo Langcay", "email" => "michaelangelo.langcay@dict.gov.ph"],
            ["name" => "Daniel", "email" => "daniel.ramirez@dict.gov.ph"],
            ["name" => "Edward C.Manuel", "email" => "edward.manuel@dict.gov.ph"],
            ["name" => "MARILYN ROBLES", "email" => "marilyn.robles@dict.gov.ph"],
            ["name" => "Markjohn Tumaliuan", "email" => "markjohn.tumaliuan@dict.gov.ph"],
            ["name" => "Arjay Niño A. Laggui", "email" => "arjay.laggui@dict.gov.ph"],
            ["name" => "Mariza Nova Montes", "email" => "mariza.montes@dict.gov.ph"],
            ["name" => "Mar Elvison Baquiran", "email" => "mar.baquiran@dict.gov.ph"],
            ["name" => "Jei Ariston Jimenez", "email" => "jeiariston.jimenez@dict.gov.ph"],
            ["name" => "Darlene Joy Seguritan", "email" => "darlenejoy.seguritan@dict.gov.ph"],
            ["name" => "Leah Galarosa Galolo", "email" => "leah.galolo@dict.gov.ph"],
            ["name" => "roland hubalde", "email" => "roland.hubalde@dict.gov.ph"],
            ["name" => "adel cano", "email" => "delmo.cano@dict.gov.ph"],
            ["name" => "BRYAN TOMAS", "email" => "bryan.tomas@dict.gov.ph"],
            ["name" => "Jenny Prudenciado", "email" => "jenny.prudenciado@dict.gov.ph"],
            ["name" => "Joey Mark Elchico", "email" => "joeymark.elchico@dict.gov.ph"],
            ["name" => "Yancee Kearvin Kyle Rafer", "email" => "kyle.rafer@dict.gov.ph"],
            ["name" => "Vladimir Viktor G. Nuval", "email" => "vladimir.nuval@dict.gov.ph"],
            ["name" => "Kyle Ruzzel Suyu", "email" => "kyle.suyu@dict.gov.ph"],
            ["name" => "Cirilo Nacino Gazzingan Jr", "email" => "jr.gazzingan@dict.gov.ph"],
            ["name" => "Roel Jimenez", "email" => "roel.jimenez@dict.gov.ph"],
            ["name" => "Maria Kristine T. Valdez", "email" => "kristine.valdez@dict.gov.ph"],
            ["name" => "Maricar S. Pecson", "email" => "maricar.pecson@dict.gov.ph"],
            ["name" => "Mario Decapia Jr.", "email" => "mariodecapia474@gmail.com"],
            ["name" => "Joyce Ann Urdillas", "email" => "joyceanne.urdillas@dict.gov.ph"],
            ["name" => "Christian Dale Aguda", "email" => "christiandale.aguda@dict.gov.ph"],
            ["name" => "Mario Decapia Jr.", "email" => "mario.decapia@dict.gov.ph"],
            ["name" => "Christopher Eleeson Capili", "email" => "christopher.capili@dict.gov.ph"],
            ["name" => "Marc Ivan Guillermo", "email" => "marcivan.guillermo@dict.gov.ph"],
            ["name" => "Jose Cabacungan", "email" => "jose.cabacungan@dict.gov.ph"],
            ["name" => "Lanie Cabacungan", "email" => "lanie.cabacungan@dict.gov.ph"],
            ["name" => "Rogelio Layugan", "email" => "rogelio.layugan@dict.gov.ph"],
            ["name" => "Edward Mark Argonza", "email" => "edwardmark.argonza@dict.gov.ph"],
            ["name" => "Diether Abad", "email" => "diether.abad@dict.gov.ph"],
            ["name" => "MISS Region 2", "email" => "miss.region2@dict.gov.ph"],
            ["name" => "Jaymar Recolizado", "email" => "jaymar.recolizado@dict.gov.ph"],
            ["name" => "Debora Backiawan", "email" => "debora.backiawan@dict.gov.ph"],
            ["name" => "Virginia Baculi", "email" => "gie.baculi@dict.gov.ph"],    
        ];

        foreach ($team as $member) {
            $plainPassword = Str::random(12);
            $hashedPassword = Hash::make($plainPassword);
            $newUser = User::create([
                'name' => $member['name'],
                'email' => $member['email'],
                'password' => $hashedPassword,
                'email_verified_at' => now(),
            ]);
            if(config('modules.send_credentials')) {
                $newUser->notify(new \App\Notifications\AccountCreatedNotification($plainPassword));
            } else {
                //for debugging
                if($member['email'] == "karlsteven.maddela@dict.gov.ph") {
                    $newUser->notify(new \App\Notifications\AccountCreatedNotification($plainPassword));
                }
            }
        }

        $settings = [
            [
                "setting" => "director", 
                "user_id" => 1, 
            ],
            [
                "setting" => "ard", 
                "user_id" => 2, 
            ],
            [
                "setting" => "afdchief", 
                "user_id" => 3, 
            ],
            [
                "setting" => "todchief", 
                "user_id" => 4, 
            ],
            [
                "setting" => "receiver", 
                "user_id" => 1, 
            ],
            [
                "setting" => "receiver", 
                "user_id" => 2, 
            ],
            [
                "setting" => "receiver", 
                "user_id" => 3, 
            ],
            [
                "setting" => "receiver", 
                "user_id" => 9, 
            ],
            [
                "setting" => "forwarder", 
                "user_id" => 9, 
            ],
            [
                "setting" => "endorser", 
                "user_id" => 1, 
            ],
            [
                "setting" => "endorser", 
                "user_id" => 2, 
            ],
            [
                "setting" => "monitorer", 
                "user_id" => 1, 
            ],
            [
                "setting" => "monitorer", 
                "user_id" => 4, 
            ],
            [
                "setting" => "monitorer", 
                "user_id" => 2, 
            ],
            [
                "setting" => "monitorer", 
                "user_id" => 3, 
            ],
            [
                "setting" => "monitorer", 
                "user_id" => 67, 
            ],
            [
                "setting" => "monitorer", 
                "user_id" => 9, 
            ],
            [
                "setting" => "monitorer", 
                "user_id" => 67, 
            ],
            [
                "setting" => "super", 
                "user_id" => 13, 
            ],
            [
                "setting" => "super", 
                "user_id" => 9, 
            ],
            [
                "setting" => "super", 
                "user_id" => 67, 
            ],
        ];

        foreach ($settings as &$s) {
            $s['created_at'] = now();
            $s['updated_at'] = now();
        }
        Setting::insert($settings);
    }
}
