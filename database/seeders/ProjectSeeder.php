<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Scene;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Buscar o primeiro usuário ou criar um de teste
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Usuário Teste',
                'email' => 'teste@theatreflux.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);
        }

        // Projeto 1: Peça Clássica
        $project1 = Project::create([
            'title' => 'Romeu e Julieta',
            'description' => 'Adaptação moderna da clássica peça de Shakespeare',
            'user_id' => $user->id,
            'is_public' => true,
            'data' => [
                'stage_width' => 800,
                'stage_height' => 600,
                'background_color' => '#f0f0f0',
                'grid_enabled' => true
            ],
            'settings' => [
                'auto_save' => true,
                'auto_save_interval' => 30,
                'show_grid' => true,
                'snap_to_grid' => false
            ],
            'last_saved_at' => now()
        ]);

        // Cenas do Projeto 1
        Scene::create([
            'project_id' => $project1->id,
            'name' => 'Ato I - O Encontro',
            'description' => 'Romeu e Julieta se conhecem no baile',
            'order' => 1,
            'data' => [
                'actors' => [
                    [
                        'id' => 'romeo',
                        'name' => 'Romeu',
                        'x' => 200,
                        'y' => 300,
                        'costume' => 'nobre_azul',
                        'props' => []
                    ],
                    [
                        'id' => 'juliet',
                        'name' => 'Julieta',
                        'x' => 600,
                        'y' => 300,
                        'costume' => 'vestido_rosa',
                        'props' => []
                    ]
                ],
                'props' => [
                    [
                        'id' => 'balcony',
                        'name' => 'Sacada',
                        'x' => 500,
                        'y' => 150,
                        'width' => 200,
                        'height' => 100
                    ]
                ]
            ],
            'stage_config' => [
                'lighting' => 'warm',
                'music' => 'romantic_theme.mp3',
                'backdrop' => 'palace_ballroom.jpg'
            ]
        ]);

        Scene::create([
            'project_id' => $project1->id,
            'name' => 'Ato II - A Sacada',
            'description' => 'A famosa cena da sacada',
            'order' => 2,
            'data' => [
                'actors' => [
                    [
                        'id' => 'romeo',
                        'name' => 'Romeu',
                        'x' => 150,
                        'y' => 400,
                        'costume' => 'casual_noturno',
                        'props' => []
                    ],
                    [
                        'id' => 'juliet',
                        'name' => 'Julieta',
                        'x' => 500,
                        'y' => 200,
                        'costume' => 'camisola_branca',
                        'props' => []
                    ]
                ],
                'props' => [
                    [
                        'id' => 'balcony',
                        'name' => 'Sacada',
                        'x' => 450,
                        'y' => 150,
                        'width' => 200,
                        'height' => 150
                    ],
                    [
                        'id' => 'moon',
                        'name' => 'Lua',
                        'x' => 700,
                        'y' => 50,
                        'width' => 80,
                        'height' => 80
                    ]
                ]
            ],
            'stage_config' => [
                'lighting' => 'moonlight',
                'music' => 'night_serenade.mp3',
                'backdrop' => 'garden_night.jpg'
            ]
        ]);

        // Projeto 2: Peça Infantil
        $project2 = Project::create([
            'title' => 'Os Três Porquinhos',
            'description' => 'Versão teatral do conto clássico infantil',
            'user_id' => $user->id,
            'is_public' => false,
            'data' => [
                'stage_width' => 1000,
                'stage_height' => 700,
                'background_color' => '#87CEEB',
                'grid_enabled' => true
            ],
            'settings' => [
                'auto_save' => true,
                'auto_save_interval' => 60,
                'show_grid' => false,
                'snap_to_grid' => true
            ],
            'last_saved_at' => now()
        ]);

        // Cena do Projeto 2
        Scene::create([
            'project_id' => $project2->id,
            'name' => 'A Casa de Tijolos',
            'description' => 'O lobo tenta derrubar a casa do terceiro porquinho',
            'order' => 1,
            'data' => [
                'actors' => [
                    [
                        'id' => 'pig1',
                        'name' => 'Porquinho 1',
                        'x' => 100,
                        'y' => 400,
                        'costume' => 'porco_rosa',
                        'props' => []
                    ],
                    [
                        'id' => 'pig2',
                        'name' => 'Porquinho 2',
                        'x' => 200,
                        'y' => 400,
                        'costume' => 'porco_azul',
                        'props' => []
                    ],
                    [
                        'id' => 'pig3',
                        'name' => 'Porquinho 3',
                        'x' => 500,
                        'y' => 350,
                        'costume' => 'porco_verde',
                        'props' => []
                    ],
                    [
                        'id' => 'wolf',
                        'name' => 'Lobo Mau',
                        'x' => 300,
                        'y' => 450,
                        'costume' => 'lobo_cinza',
                        'props' => []
                    ]
                ],
                'props' => [
                    [
                        'id' => 'brick_house',
                        'name' => 'Casa de Tijolos',
                        'x' => 450,
                        'y' => 200,
                        'width' => 250,
                        'height' => 200
                    ],
                    [
                        'id' => 'trees',
                        'name' => 'Árvores',
                        'x' => 50,
                        'y' => 100,
                        'width' => 150,
                        'height' => 200
                    ]
                ]
            ],
            'stage_config' => [
                'lighting' => 'daylight',
                'music' => 'children_theme.mp3',
                'backdrop' => 'forest_clearing.jpg'
            ]
        ]);

        $this->command->info('Projetos de exemplo criados com sucesso!');
    }
}
