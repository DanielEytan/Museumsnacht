<?php
use craft\elements\Entry;
use craft\elements\Category;
use craft\helpers\UrlHelper;

return [
    'endpoints' => [

/*
************************************************************
*
*                        PROGRAM
*
************************************************************
*/

            'program.json' => function() {
                return [
                    'elementType' => Entry::class,
                    'cache' => false,
                    'elementsPerPage' => 999,
                    'paginate' => false,

                    'criteria' => [
                        'section' => 'program',
                        'type' =>'institution', 
                        'with' => ['events', 'events.time', 'events.themes', 'events.kindOfEvent', 'events.languages' ],
                    ],

                    'transformer' => function(Entry $entry) {

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
                                'themes' => array_map( function (Category $category) {
                                    return [
                                        'id' => $category->id,
                                        'title' => $category->title
                                    ];
                                }, $event->themes->find()),
                                'kindOfEvent' => array_map( function (Category $category) {
                                    return [
                                        'id' => $category->id,
                                        'title' => $category->title
                                    ];
                                }, $event->kindOfEvent->find()),
                                'languages' => array_map( function (Category $category) {
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
                            'jsonUrl' => UrlHelper::url("program/{$entry->id}.json"),
                            'events' => $eventsInfo,
                            'shuttleLine' => array_map( function (Category $category) {
                                return [
                                    'title' => $category->title,
                                    'color' => $category->color,
                                    'exceptions' => $category->exceptions
                                ];
                            }, $entry->shuttleLine->find()),
                        ];


                    },
                ];
            },

/*
************************************************************
*
*                        PROGRAM ENTY
*
************************************************************
*/

            'program/<entryId:\d+>.json' => function($entryId) {
                return [
                    'elementType' => Entry::class,
                    'cache' => true,
                    'criteria' => ['id' => $entryId],
                    'one' => true,
                    'transformer' => function(Entry $entry) {
                        return [
                            'title' => $entry->title,
                            'url' => $entry->url
                        ];
                    },
                ];
            },

/*
************************************************************
*
*                        THEMES
*
************************************************************
*/

            'themes.json' => [
                'elementType' => Category::class,
                'cache' => true,
                'criteria' => ['group' => 'themes'],
                'transformer' => function(Category $entry) {
                    return [
                        'title' => $entry->title,
                        'id' => $entry->id
                    ];
                },
            ],

/*
************************************************************
*
*                        EVENTS
*
************************************************************
*/

            'events.json' => [
                'elementType' => Category::class,
                'cache' => true,
                'criteria' => ['group' => 'kindOfEvent'],
                'transformer' => function(Category $entry) {
                    return [
                        'title' => $entry->title,
                        'id' => $entry->id
                    ];
                },
            ],

/*
************************************************************
*
*                        LANGUAGES
*
************************************************************
*/

            'languages.json' => [
                'elementType' => Category::class,
                'cache' => true,
                'criteria' => ['group' => 'languages'],
                'transformer' => function(Category $entry) {
                    return [
                        'title' => $entry->title,
                        'id' => $entry->id
                    ];
                },
            ],

/*
************************************************************
*
*                        INSTITUTION
*
************************************************************
*/

            'institution.json' => [
                'elementType' => Entry::class,
                'cache' => true,
                'criteria' => ['section' => 'program', 'type' =>'institution'],
                'transformer' => function(Entry $entry) {
                 $photos = [];
                 foreach ($entry->programImg as $photo) {
                    $photos[] = $photo->url;
                }
                return [
                    'title' => $entry->title,
                    'id' => $entry->id,
                    'number' => $entry->number,
                    'address' => $entry->address,
                    'location' => $entry->location,
                    'journey' => (string) $entry->journey,
                    'url' => $entry->url,
                    'advanceSale' => $entry->advanceSale,
                    'wifi' => $entry->wifi,
                    'accessibility' => $entry->accessibility,
                    'programmTitle' => (string) $entry->programmTitle,
                    'photos' => $photos,
                    'shuttleLine' => array_map( function (Category $category) {
                        return [
                            'title' => $category->title,
                            'color' => $category->color
                        ];
                    }, $entry->shuttleLine->find()),
                ];
            },
        ],

/*
************************************************************
*
*                        PROGRAMEVENT
*
************************************************************
*/

        'programevent.json' => [
            'elementType' => Entry::class,
            'cache' => false,
            'elementsPerPage' => 999,
            'paginate' => false,
            // 'limit' => 999,
            'criteria' => ['section' => 'program','type' =>'Event'],
            'transformer' => function(Entry $entry) {
                // HeaderHelper::setHeader([
                //     'Access-Control-Allow-Origin' => '*'
                // ]);
                $parent = $entry->getParent();
                $timeBlocks = [];
                foreach ($entry->time as $block) {
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
                return [
                    'title' => $entry->title,
                    'url' => $entry->url,
                    'id' => $entry->id,
                    'description' => (string) $entry->description,
                    'additionalLang' => (string) $entry->additionalLang,
                    'level' => $entry->level,
                    'time' => $timeBlocks,
                    'themes' => array_map( function (Category $category) {
                        return [
                            'id' => $category->id,
                            'title' => $category->title
                        ];
                    }, $entry->themes->find()),
                    'kindOfEvent' => array_map( function (Category $category) {
                        return [
                            'id' => $category->id,
                            'title' => $category->title
                        ];
                    }, $entry->kindOfEvent->find()),
                    'languages' => array_map( function (Category $category) {
                        return [
                            'id' => $category->id,
                            'title' => $category->title
                        ];
                    }, $entry->languages->find()),
                    // 'categories' => $categories,
                    'parent' => $parent ? [
                        'title' => $parent->title,
                        'number' => $parent->number,
                        'url' => $parent->url,
                        'shuttleLine' => array_map( function (Category $category) {
                            return [
                                'title' => $category->title,
                                'color' => $category->color
                            ];
                        }, $parent->shuttleLine->find()),
                    ] : null,

                ];
            },
        ],
    ]
];