<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bibliotech.test'],
            ['name' => 'Admin BiblioTech', 'password' => 'password', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'membre@bibliotech.test'],
            ['name' => 'Membre Demo', 'password' => 'password', 'role' => 'membre']
        );

        $categories = collect(['Developpement', 'Business', 'Productivite', 'Roman', 'Science'])
            ->mapWithKeys(fn (string $name) => [$name => Category::firstOrCreate(['nom' => $name])]);

        $books = [
            ['Clean Code', 'Robert C. Martin', '9780132350884', 'clean_code.webp', 4, 'Developpement'],
            ['Deep Work', 'Cal Newport', '9781455586691', 'deepwork.jpg', 3, 'Productivite'],
            ['Atomic Habits', 'James Clear', '9780735211292', 'atomic_habits.jpg', 5, 'Productivite'],
            ['The Lean Startup', 'Eric Ries', '9780307887894', 'lean.jpg', 2, 'Business'],
            ['Hooked', 'Nir Eyal', '9781591847786', 'hooked.jpg', 2, 'Business'],
            ['Le Petit Prince', 'Antoine de Saint-Exupery', '9780156012195', 'petit_prince.jpg', 1, 'Roman'],
            ['Les Miserables', 'Victor Hugo', '9780451419439', 'miserables.jpg', 2, 'Roman'],
            ['Sapiens', 'Yuval Noah Harari', '9780062316097', 'sapiens.jfif', 3, 'Science'],
            ['Zero to One', 'Peter Thiel', '9780804139298', 'zero.jpg', 2, 'Business'],
            ['Rich Dad Poor Dad', 'Robert Kiyosaki', '9781612680194', 'richdad.jpg', 4, 'Business'],
            ['Think and Grow Rich', 'Napoleon Hill', '9781585424337', 'think.jpg', 3, 'Business'],
            ['The 7 Habits', 'Stephen R. Covey', '9781982137274', '7habits.jpg', 2, 'Productivite'],
        ];

        foreach ($books as [$title, $author, $isbn, $image, $copies, $category]) {
            Livre::updateOrCreate(
                ['isbn' => $isbn],
                [
                    'titre' => $title,
                    'auteur' => $author,
                    'image' => $image,
                    'nombre_exemplaires' => $copies,
                    'statut' => $copies > 0 ? Livre::STATUT_DISPONIBLE : Livre::STATUT_INDISPONIBLE,
                    'category_id' => $categories[$category]->id,
                ]
            );
        }
    }
}
