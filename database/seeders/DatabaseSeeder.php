<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $authors = [
            ['firstname' => 'Albert', 'lastname' => 'Camus'],
            ['firstname' => 'Victor', 'lastname' => 'Hugo'],
            ['firstname' => 'Gustave', 'lastname' => 'Flaubert'],
            ['firstname' => 'Antoine', 'lastname' => 'de Saint-Exupéry'],
            ['firstname' => 'Émile', 'lastname' => 'Zola'],
            ['firstname' => 'Stendhal', 'lastname' => ''],
            ['firstname' => 'Voltaire', 'lastname' => ''],
            ['firstname' => 'Alexandre', 'lastname' => 'Dumas'],
            ['firstname' => 'Guy', 'lastname' => 'de Maupassant']
        ];

        $editors = [
            ['name' => 'Gallimard'],
            ['name' => 'Le Livre de Poche'],
            ['name' => 'Folio'],
            ['name' => 'Flammarion']
        ];

        foreach ($authors as $author) {
            DB::table('authors')->insert($author);
        }

        foreach ($editors as $editor) {
            DB::table('editors')->insert($editor);
        }

        $authorIds = DB::table('authors')->pluck('id', 'lastname')->toArray();
        $editorIds = DB::table('editors')->pluck('id', 'name')->toArray();

        $books = [
            [
                'isbn' => '9782070360024',
                'title' => 'L\'Étranger',
                'thumbnail' => 'https://www.gallimard.fr/system/files/styles/medium/private/migrations/ouvrages/couvertures/A21200.jpg.webp?itok=FxgbeI7B',
                'average_rating' => 4.2,
                'ratings_count' => 15847,
                'author' => $authorIds['Camus'],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['existentialisme', 'absurde', 'Algérie', 'philosophie', 'classique']),
                'pages' => 186,
                'summary' => 'Meursault, employé de bureau à Alger, apprend la mort de sa mère. Après l\'enterrement, il tue un Arabe sur une plage. Au procès, c\'est autant son indifférence que son crime qui sont jugés.',
                'publish_year' => 1942
            ],
            [
                'isbn' => '9782070411986',
                'title' => 'Les Misérables',
                'thumbnail' => 'https://www.gallimard.fr/system/files/migrations/ouvrages/couvertures/A14222.jpg',
                'average_rating' => 4.5,
                'ratings_count' => 32156,
                'author' => $authorIds['Hugo'],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['histoire', 'Paris', '19ème siècle', 'justice sociale', 'révolution']),
                'pages' => 1664,
                'summary' => 'L\'histoire de Jean Valjean, ancien forçat devenu maire, poursuivi par l\'inspecteur Javert. Une fresque de la France du XIXe siècle à travers le destin de personnages inoubliables.',
                'publish_year' => 1862
            ],
            [
                'isbn' => '9782070368228',
                'title' => 'Madame Bovary',
                'thumbnail' => 'https://www.gallimard.fr/system/files/migrations/ouvrages/couvertures/G05078.jpg',
                'average_rating' => 3.8,
                'ratings_count' => 18942,
                'author' => $authorIds['Flaubert'],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['réalisme', 'adultère', 'bourgeoisie', 'Normandie', 'tragédie']),
                'pages' => 464,
                'summary' => 'Emma Bovary, femme d\'un médecin de campagne, s\'ennuie dans sa vie provinciale. Elle cherche l\'amour et le rêve dans des aventures extraconjugales qui la mèneront à sa perte.',
                'publish_year' => 1857
            ],
            [
                'isbn' => '9782070413119',
                'title' => 'Le Petit Prince',
                'thumbnail' => 'https://www.gallimard-jeunesse.fr/assets/media/cache/cover_medium/gallimard_img/image/A66722.jpg',
                'average_rating' => 4.7,
                'ratings_count' => 45623,
                'author' => $authorIds['de Saint-Exupéry'],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['conte', 'enfance', 'philosophie', 'amitié', 'voyage']),
                'pages' => 96,
                'summary' => 'L\'histoire d\'un petit prince qui voyage de planète en planète et de sa rencontre avec un aviateur dans le désert. Une réflexion poétique sur l\'amitié, l\'amour et la condition humaine.',
                'publish_year' => 1943
            ],
            [
                'isbn' => '9782070379279',
                'title' => 'Germinal',
                'thumbnail' => 'https://www.gallimard.fr/system/files/migrations/ouvrages/couvertures/A41142.jpg',
                'average_rating' => 4.1,
                'ratings_count' => 12356,
                'author' => $authorIds['Zola'],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['naturalisme', 'mine', 'prolétariat', 'grève', 'condition ouvrière']),
                'pages' => 591,
                'summary' => 'Étienne Lantier arrive dans le bassin minier du Nord. Il découvre la misère des mineurs et devient l\'un des meneurs de la grève qui éclate contre la Compagnie des mines.',
                'publish_year' => 1885
            ],
            [
                'isbn' => '9782070360871',
                'title' => 'La Peste',
                'thumbnail' => 'https://www.gallimard.fr/system/files/styles/medium/private/migrations/ouvrages/couvertures/A21203.jpg.webp?itok=zEX-_hoR',
                'average_rating' => 4.3,
                'ratings_count' => 21847,
                'author' => $authorIds['Camus'],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['épidémie', 'Oran', 'solidarité', 'humanisme', 'existentialisme']),
                'pages' => 279,
                'summary' => 'Une épidémie de peste frappe la ville d\'Oran. Le docteur Rieux et ses compagnons luttent contre le fléau, révélant la grandeur et la petitesse de l\'homme face à l\'absurde.',
                'publish_year' => 1947
            ],
            [
                'isbn' => '9782253004226',
                'title' => 'Notre-Dame de Paris',
                'thumbnail' => 'https://static.fnac-static.com/multimedia/PE/Images/FR/NR/dd/5e/13/1269469/1507-1/tsp20250101075645/Notre-Dame-de-Paris.jpg',
                'average_rating' => 4.0,
                'ratings_count' => 16789,
                'author' => $authorIds['Hugo'],
                'editor' => $editorIds['Le Livre de Poche'],
                'keywords' => json_encode(['moyen âge', 'architecture', 'amour', 'Paris', 'cathédrale']),
                'pages' => 698,
                'summary' => 'L\'histoire de la bohémienne Esmeralda, du bossu Quasimodo et de l\'archidiacre Frollo, dans le Paris du XVe siècle, avec Notre-Dame comme témoin et acteur du drame.',
                'publish_year' => 1831
            ],
            [
                'isbn' => '9782070400034',
                'title' => 'Le Rouge et le Noir',
                'thumbnail' => 'https://www.gallimard.fr/system/files/migrations/ouvrages/couvertures/580534.jpg',
                'average_rating' => 3.9,
                'ratings_count' => 13654,
                'author' => $authorIds[''],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['ambition', 'Restauration', 'amour', 'société', 'hypocrisie']),
                'pages' => 750,
                'summary' => 'Julien Sorel, fils de charpentier, tente de s\'élever socialement sous la Restauration. Son ambition et ses amours le mèneront devant l\'échafaud.',
                'publish_year' => 1830
            ],
            [
                'isbn' => '9782070413928',
                'title' => 'Candide',
                'thumbnail' => 'https://www.gallimard.fr/system/files/styles/medium/private/migrations/ouvrages/couvertures/005970.jpg.webp?itok=IWqfd1fW',
                'average_rating' => 4.0,
                'ratings_count' => 19847,
                'author' => $authorIds[''],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['conte philosophique', 'optimisme', 'satire', 'Lumières', 'voyage']),
                'pages' => 148,
                'summary' => 'Candide, chassé du château où il vivait heureux, parcourt le monde et découvre que tout ne va pas pour le mieux dans le meilleur des mondes possibles.',
                'publish_year' => 1759
            ],
            [
                'isbn' => '9782070360857',
                'title' => 'L\'Assommoir',
                'thumbnail' => 'https://images.epagine.fr/436/9782070411436_1_75.jpg',
                'average_rating' => 3.7,
                'ratings_count' => 8965,
                'author' => $authorIds['Zola'],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['naturalisme', 'alcoolisme', 'Belleville', 'condition ouvrière', 'déchéance']),
                'pages' => 502,
                'summary' => 'Gervaise Macquart monte à Paris et tente de mener une vie honnête. Mais l\'alcoolisme de son mari et la misère sociale auront raison de ses espoirs.',
                'publish_year' => 1877
            ],
            [
                'isbn' => '9782253096344',
                'title' => 'Le Comte de Monte-Cristo',
                'thumbnail' => 'https://m.media-amazon.com/images/I/818khguS0OL.jpg',
                'average_rating' => 4.6,
                'ratings_count' => 28956,
                'author' => $authorIds['Dumas'],
                'editor' => $editorIds['Le Livre de Poche'],
                'keywords' => json_encode(['aventure', 'vengeance', 'justice', 'Marseille', 'évasion']),
                'pages' => 1254,
                'summary' => 'Edmond Dantès, injustement emprisonné, s\'évade du château d\'If après quatorze ans de captivité et revient sous l\'identité du comte de Monte-Cristo pour se venger.',
                'publish_year' => 1844
            ],
            [
                'isbn' => '9782070412198',
                'title' => 'Bel-Ami',
                'thumbnail' => 'https://images.epagine.fr/879/9791035804879_1_75.jpg',
                'average_rating' => 3.8,
                'ratings_count' => 11234,
                'author' => $authorIds['de Maupassant'],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['arrivisme', 'presse', 'Belle Époque', 'Paris', 'cynisme']),
                'pages' => 416,
                'summary' => 'Georges Duroy, surnommé Bel-Ami, gravit l\'échelle sociale parisienne grâce à son charme et à ses relations féminines dans le monde de la presse et de la politique.',
                'publish_year' => 1885
            ],
            [
                'isbn' => '9782070409228',
                'title' => 'Thérèse Raquin',
                'thumbnail' => 'https://m.media-amazon.com/images/I/91w+cqlQOPL._AC_UF1000,1000_QL80_.jpg',
                'average_rating' => 3.6,
                'ratings_count' => 7845,
                'author' => $authorIds['Zola'],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['naturalisme', 'adultère', 'crime', 'remords', 'psychologie']),
                'pages' => 254,
                'summary' => 'Thérèse, mariée à son cousin chétif Camille, devient la maîtresse de Laurent. Ensemble, ils assassinent le mari, mais le remords les ronge jusqu\'à la folie.',
                'publish_year' => 1867
            ],
            [
                'isbn' => '9782070371440',
                'title' => 'Boule de Suif',
                'thumbnail' => 'https://www.gallimard.fr/system/files/migrations/ouvrages/couvertures/A45819.jpg',
                'average_rating' => 4.1,
                'ratings_count' => 9654,
                'author' => $authorIds['de Maupassant'],
                'editor' => $editorIds['Gallimard'],
                'keywords' => json_encode(['nouvelle', 'guerre 1870', 'hypocrisie', 'prostitution', 'bourgeoisie']),
                'pages' => 64,
                'summary' => 'Pendant la guerre de 1870, des bourgeois voyagent avec une prostituée surnommée Boule de Suif. Ils la méprisent mais n\'hésitent pas à l\'utiliser pour leurs intérêts.',
                'publish_year' => 1880
            ],
            [
                'isbn' => '9782253006329',
                'title' => 'Les Trois Mousquetaires',
                'thumbnail' => 'https://media.hachette.fr/fit-in/780x1280/imgArticle/LGFLIVREDEPOCHE/2023/9782253008880-001-X.jpeg?source=web',
                'average_rating' => 4.4,
                'ratings_count' => 34567,
                'author' => $authorIds['Dumas'],
                'editor' => $editorIds['Le Livre de Poche'],
                'keywords' => json_encode(['aventure', 'amitié', 'épée', 'Louis XIII', 'Richelieu']),
                'pages' => 694,
                'summary' => 'D\'Artagnan quitte la Gascogne pour Paris et rencontre Athos, Porthos et Aramis. Ensemble, ils servent le roi et combattent les intrigues du cardinal de Richelieu.',
                'publish_year' => 1844
            ]
        ];

        foreach ($books as $book) {
            DB::table('books')->insert($book);
        }
    }
}
