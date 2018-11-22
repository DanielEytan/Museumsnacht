<?php
use craft\elements\Entry;
use craft\helpers\UrlHelper;

return [
    'endpoints' => [
        'visit.json' => [
            'elementType' => 'craft\elements\Entry',
            'cache' => true,
            'criteria' => ['section' => 'visit'],
            'transformer' => function(EntryModel $entry) {
                // Create an array of all the "Body" Matrix field's blocks
                $listBlocks = [];
                foreach ($entry->boxOfficeLocations as $block) {
                    switch ($block->type->handle) {
                        case 'block':
                        $listBlocks[] = [
                            'listContent' => $block->list->getParsedContent(),
                        ];
                        break;
                    }
                }
                return [
                    'list' => $listBlocks
                ];
            },
        ],
        'program.json' => [
            // 'elementType' => 'craft\elements\Entry',
            'elementType' => craft\elements\Entry::class,
            'cache' => true,
            'elementsPerPage' => 999,
            'paginate' => false,
            // 'limit' => 999,

            'criteria' => [
                'section' => 'program',
                'type' =>'institution', 

                'with' => ['events', 'events.time', 'events.themes', 'events.kindOfEvent', 'events.languages' ],
            ],
            'transformer' => function(EntryModel $entry) {

                //get descendants
                $events = $entry->getDescendants(2);
                $eventsInfo = [];

                foreach ($events as $event) {

                   //read time matrix
                    $timeBlocks = [];
                    foreach ($event->time as $block) {
                        switch ($block->type->handle) {
                            case 'setTimes':
                            $timeBlocks[] = [
                                'type' => 'setTimes',
                                'start' => $block->start,
                                'duration' => $block->duration
                            ];
                            break;
                            case 'continuous':
                            $timeBlocks[] = [
                                'type' => 'continuous',
                                'start' => $block->start,
                                'end' => $block->end
                            ];
                            break;
                            case 'iterating':
                            $timeBlocks[] = [
                                'type' => 'iterating',
                                'start' => $block->start,
                                'end' => $block->end,
                                'frequency' => $block->frequency,
                                'duration' => $block->duration
                            ];
                            break;
                        }
                    }



                    $eventsInfo[] = [
                        'title' => $event->title,
                        'id' => $event->id,
                        'description' => (string) $event->description,
                        'additionalLang' => (string) $event->additionalLang,
                        'time' => $timeBlocks,
                        'themes' => array_map( function (CategoryModel $category) {
                            return [
                                'id' => $category->id,
                                'title' => $category->title
                            ];
                        }, $event->themes->find()),
                        'kindOfEvent' => array_map( function (CategoryModel $category) {
                            return [
                                'id' => $category->id,
                                'title' => $category->title
                            ];
                        }, $event->kindOfEvent->find()),
                        'languages' => array_map( function (CategoryModel $category) {
                            return [
                                'id' => $category->id,
                                'title' => $category->title
                            ];
                        }, $event->languages->find()),
                    ];
                }


                //return json structure
                return [
                    'title' => $entry->title,
                    'id' => $entry->id,
                    'url' => $entry->url,
                    'number' => $entry->number,
                    'address' => $entry->address,
                    'journey' => $entry->journey,
                    'food' => $entry->food,
                    'programmTitle' => (string) $entry->programmTitle,
                    'programmText' => (string) $entry->programmText,
                    'jsonUrl' => UrlHelper::getUrl("program/{$entry->id}.json"),
                    'events' => $eventsInfo,
                    'shuttleLine' => array_map( function (CategoryModel $category) {
                        return [
                            'title' => $category->title,
                            'color' => $category->color,
                            'exceptions' => $category->exceptions
                        ];
                    }, $entry->shuttleLine->find()),
                ];
            },
        ],
    //     'program/<entryId:\d+>.json' => function($entryId) {
    //         return [
    //             'elementType' => 'craft\elements\Entry',
    //             'cache' => true,
    //             'criteria' => ['id' => $entryId],
    //             'one' => true,
    //             'transformer' => function(EntryModel $entry) {
    //                 return [
    //                     'title' => $entry->title,
    //                     'url' => $entry->url
    //                 ];
    //             },
    //         ];
    //     },
    //     'themes.json' => [
    //         'elementType' => 'craft\elements\Category',
    //         'cache' => true,
    //         'criteria' => ['group' => 'themes'],
    //         'transformer' => function(CategoryModel $entry) {
    //             return [
    //                 'title' => $entry->title,
    //                 'id' => $entry->id
    //             ];
    //         },
    //     ],
    //     'events.json' => [
    //         'elementType' => 'craft\elements\Category',
    //         'cache' => true,
    //         'criteria' => ['group' => 'kindOfEvent'],
    //         'transformer' => function(CategoryModel $entry) {
    //             return [
    //                 'title' => $entry->title,
    //                 'id' => $entry->id
    //             ];
    //         },
    //     ],
    //     'languages.json' => [
    //         'elementType' => 'craft\elements\Category',
    //         'cache' => true,
    //         'criteria' => ['group' => 'languages'],
    //         'transformer' => function(CategoryModel $entry) {
    //             return [
    //                 'title' => $entry->title,
    //                 'id' => $entry->id
    //             ];
    //         },
    //     ],
    //     'institution.json' => [
    //         'elementType' => 'craft\elements\Entry',
    //         'cache' => true,
    //         'criteria' => ['section' => 'program', 'type' =>'institution'],
    //         'transformer' => function(EntryModel $entry) {
    //          $photos = [];
    //          foreach ($entry->programImg as $photo) {
    //             $photos[] = $photo->url;
    //         }
    //         return [
    //             'title' => $entry->title,
    //             'id' => $entry->id,
    //             'number' => $entry->number,
    //             'address' => $entry->address,
    //             'location' => $entry->location,
    //             'journey' => (string) $entry->journey,
    //             'url' => $entry->url,
    //             'advanceSale' => $entry->advanceSale,
    //             'wifi' => $entry->wifi,
    //             'accessibility' => $entry->accessibility,
    //             'programmTitle' => (string) $entry->programmTitle,
    //             'photos' => $photos,
    //             'shuttleLine' => array_map( function (CategoryModel $category) {
    //                 return [
    //                     'title' => $category->title,
    //                     'color' => $category->color
    //                 ];
    //             }, $entry->shuttleLine->find()),
    //         ];
    //     },
    // ],
    // 'locations.json' => [
    //     'elementType' => 'craft\elements\Entry',
    //     'cache' => true,
    //         // 'paginate' => false,
    //     'criteria' => [
    //         'section' => 'program',
    //         'type' => 'institution'
    //     ],
    //     'transformer' => function(EntryModel $entry) {
    //         $photos = [];
    //         foreach ($entry->programImg as $photo) {
    //             $photos[] = $photo->url;
    //         }
    //         return [
    //             'title' => $entry->title,
    //             'lat' => $entry->map->lat,
    //             'lng' => $entry->map->lng,
    //             'number' => $entry->number,
    //             'url' => $entry->url,
    //             'address' => $entry->address,
    //             'journey' => (string) $entry->journey,
    //             'advanceSale' => $entry->advanceSale,
    //             'accessibility' => $entry->accessibility,
    //             'photos' => $photos,
                
    //             'shuttleLine' => array_map( function (CategoryModel $category) {
    //                 return [
    //                     'title' => $category->title,
    //                     'color' => $category->color
    //                 ];
    //             }, $entry->shuttleLine->find()),
    //         ];
    //     },
    // ],
    // 'downloads.json' => [
    //     'elementType' => 'craft\elements\Entry',
    //     // 'cache' => true,
    //         // 'paginate' => false,
    //     'criteria' => [
    //         'section' => 'downloads',
    //         // 'type' => 'institution'
    //     ],
    //     'transformer' => function(EntryModel $entry) {
    //             // Create an array of all the "Body" Matrix field's blocks
    //             $downloadBlock = [];
    //             foreach ($entry->downloads as $block) {
    //                 switch ($block->type->handle) {
    //                     case 'block':
    //                     $docs = [];
    //                     foreach ($block->docs as $doc) {
    //                         $docs[] = [
    //                             'url' => $doc->url,
    //                             'type' => $doc->type,
    //                         ];
    //                     }
    //                     $downloadBlock[] = [
    //                         'title' => $block->blockTitle,    
    //                         'docs' => $docs,
    //                         // 'type' => $docstype, 
    //                     ];
    //                     break;
    //                 }
    //             }
    //             return [
    //                 'block' => $downloadBlock
    //             ];
            
    //     },
    // ],
    // 'journal.json' => [
    //     'elementType' => 'craft\elements\Entry',
    //     // 'cache' => true,
    //         // 'paginate' => false,
    //     'criteria' => [
    //         'section' => 'journal',
    //         // 'type' => 'institution'
    //     ],
    //     'transformer' => function(EntryModel $entry) {
    //         $photos = [];
    //         foreach ($entry->cover as $photo) {
    //             $photos[] = $photo->url;
    //         }
    //         return [
    //             'title' => $entry->title,
    //             'url' => $entry->url,
    //             // 'type' => $entryType->type,
    //             'text' => (string) $entry->text,
    //             'excerpt' => (string) $entry->excerpt,
    //             'photos' => $photos
    //         ];
    //     },
    // ],
    // 'programevent.json' => [
    //     'elementType' => 'craft\elements\Entry',
    //     'cache' => true,
    //     'elementsPerPage' => 999,
    //     'paginate' => false,
    //     // 'limit' => 999,
    //     'criteria' => ['section' => 'program','type' =>'Event'],
    //     'transformer' => function(EntryModel $entry) {
    //         HeaderHelper::setHeader([
    //             'Access-Control-Allow-Origin' => '*'
    //         ]);
    //         $parent = $entry->getParent();
    //         $timeBlocks = [];
    //         foreach ($entry->time as $block) {
    //             switch ($block->type->handle) {
    //                 case 'setTimes':
    //                 $timeBlocks[] = [
    //                     'type' => 'setTimes',
    //                     'start' => $block->start,
    //                     'duration' => $block->duration
    //                 ];
    //                 break;
    //                 case 'continuous':
    //                 $timeBlocks[] = [
    //                     'type' => 'continuous',
    //                     'start' => $block->start,
    //                     'end' => $block->end
    //                 ];
    //                 break;
    //                 case 'iterating':
    //                 $timeBlocks[] = [
    //                     'type' => 'iterating',
    //                     'start' => $block->start,
    //                     'end' => $block->end,
    //                     'frequency' => $block->frequency,
    //                     'duration' => $block->duration
    //                 ];
    //                 break;
    //             }
    //         }
    //         return [
    //             'title' => $entry->title,
    //             'url' => $entry->url,
    //             'id' => $entry->id,
    //             'description' => (string) $entry->description,
    //             'additionalLang' => (string) $entry->additionalLang,
    //             'level' => $entry->level,
    //             'time' => $timeBlocks,
    //             'themes' => array_map( function (CategoryModel $category) {
    //                 return [
    //                     'id' => $category->id,
    //                     'title' => $category->title
    //                 ];
    //             }, $entry->themes->find()),
    //             'kindOfEvent' => array_map( function (CategoryModel $category) {
    //                 return [
    //                     'id' => $category->id,
    //                     'title' => $category->title
    //                 ];
    //             }, $entry->kindOfEvent->find()),
    //             'languages' => array_map( function (CategoryModel $category) {
    //                 return [
    //                     'id' => $category->id,
    //                     'title' => $category->title
    //                 ];
    //             }, $entry->languages->find()),
    //             // 'categories' => $categories,
    //             'parent' => $parent ? [
    //                 'title' => $parent->title,
    //                 'number' => $parent->number,
    //                 'url' => $parent->url,
    //                 'shuttleLine' => array_map( function (CategoryModel $category) {
    //                     return [
    //                         'title' => $category->title,
    //                         'color' => $category->color
    //                     ];
    //                 }, $parent->shuttleLine->find()),
    //             ] : null,

    //         ];
    //     },
    // ],
    // 'tickets.json' => [
    //     'elementType' => 'craft\elements\Entry',
    //     // 'cache' => true,
    //     'elementsPerPage' => 999,
    //     'paginate' => false,
    //     // 'limit' => 999,
    //     'criteria' => ['section' => 'tickets', 'locale' => 'en'],
    //     'transformer' => function(EntryModel $entry) {

    //         return [
    //             'tickets' => (string) $entry->tickets,
    //         ];
    //     }
    // ],
    // 'mobility.json' => [
    //     'elementType' => 'craft\elements\Entry',
    //     // 'cache' => true,
    //     'elementsPerPage' => 999,
    //     'paginate' => false,
    //     // 'limit' => 999,
    //     'criteria' => ['section' => 'mobility', 'locale' => 'en'],
    //     'transformer' => function(EntryModel $entry) {

    //         return [
    //             'mobility' => (string) $entry->mobility,
    //         ];
    //     }
    // ]
]
];