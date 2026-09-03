<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Actions\Action as FormAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Beitrag')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Titel')
                        ->required()
                        ->maxLength(150)
                        ->live(onBlur: true)
                        // Slug nur beim Anlegen mitschreiben. Bei bestehenden
                        // Beiträgen bleibt er stehen – siehe Hinweis am Feld.
                        ->afterStateUpdated(function (string $operation, $state, callable $set) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug(str_replace('.', '-', (string) $state), '-', 'de'));
                            }
                        })
                        ->columnSpanFull(),

                    TextInput::make('slug')
                        ->label('Adresse')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->prefix('/blog/')
                        ->helperText('Nach der Veröffentlichung nicht mehr ändern – bestehende Links und Google-Treffer laufen sonst ins Leere.')
                        ->columnSpanFull(),

                    Select::make('category_id')
                        ->label('Kategorie')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Entwurf',
                            'published' => 'Veröffentlicht',
                        ])
                        ->default('published')
                        ->required(),

                    DateTimePicker::make('published_at')
                        ->label('Veröffentlicht am')
                        ->seconds(false)
                        ->default(now())
                        ->helperText('Bestimmt die Sortierung. Ein Datum in der Zukunft hält den Beitrag zurück.'),

                    Select::make('thumb_fit')
                        ->label('Bildzuschnitt in der Übersicht')
                        ->options([
                            'contain' => 'Einpassen – richtig für Logos',
                            'cover' => 'Füllend – richtig für Fotos und Screenshots',
                        ])
                        ->placeholder('Füllend (Vorgabe)'),
                ]),

            Section::make('Inhalt')
                ->schema([
                    Textarea::make('teaser')
                        ->label('Teaser')
                        ->required()
                        ->rows(3)
                        ->maxLength(300)
                        // Der Teaser ist gleichzeitig Meta-Description und
                        // og:description. Google schneidet in der Trefferliste
                        // bei etwa 160 Zeichen ab.
                        ->helperText('Wird als Vorschautext, Meta-Description und beim Teilen verwendet. Ideal 120–160 Zeichen.')
                        ->columnSpanFull(),

                    MarkdownEditor::make('content')
                        ->label('Text')
                        ->required()
                        ->toolbarButtons([
                            'bold', 'italic', 'heading', 'bulletList', 'orderedList',
                            'link', 'blockquote', 'codeBlock', 'undo', 'redo',
                        ])
                        // Kurz und eingeklappt: eine Anleitung, die immer offen
                        // steht, liest man nach dem dritten Beitrag nicht mehr –
                        // sie steht dann nur noch zwischen Titel und Text.
                        ->hintAction(
                            FormAction::make('schreibhilfe')
                                ->label('Wie schreibe ich das?')
                                ->icon(Heroicon::OutlinedQuestionMarkCircle)
                                ->modalHeading('Kurz zum Textfeld')
                                ->modalDescription('Der Text wird als Markdown gespeichert und beim Anzeigen in HTML übersetzt. Die Knöpfe oben machen dasselbe wie die Zeichen unten – wer lieber tippt, tippt.')
                                ->modalContent(view('filament.hinweise.schreibhilfe'))
                                ->modalSubmitAction(false)
                                ->modalCancelActionLabel('Alles klar')
                        )
                        ->columnSpanFull(),

                    FileUpload::make('hero_image')
                        ->label('Beitragsbild')
                        ->image()
                        ->disk('inhalte')
                        ->directory('uploads/blog')
                        ->visibility('public')
                        ->imagePreviewHeight('160')
                        ->helperText('Wird als großes Bild im Beitrag und als Kachel in der Übersicht verwendet.')
                        ->columnSpanFull(),
                ]),

            Section::make('Schaltflächen am Textende')
                ->description('Erscheinen als Buttons unter dem Beitrag.')
                ->collapsed()
                ->schema([
                    Repeater::make('links')
                        ->hiddenLabel()
                        ->relationship()
                        ->columns(2)
                        ->defaultItems(0)
                        ->addActionLabel('Schaltfläche hinzufügen')
                        ->orderColumn('position')
                        ->schema([
                            TextInput::make('label')->label('Beschriftung')->required(),
                            TextInput::make('url')->label('Ziel')->url()->required(),
                        ]),
                ]),

            Section::make('Produkt')
                ->description('Nur für Shop-Beiträge. Ist ein Produkt gesetzt, zeigt der Beitrag die Produktbox statt des Beitragsbildes und blendet die Schaltflächen aus.')
                ->collapsed()
                ->schema([
                    Toggle::make('hat_produkt')
                        ->label('Dieser Beitrag bewirbt ein Produkt')
                        ->live()
                        ->dehydrated(false)
                        ->formatStateUsing(fn ($record) => $record?->product !== null),

                    Section::make()
                        ->relationship('product')
                        ->columns(2)
                        ->visible(fn (callable $get) => (bool) $get('hat_produkt'))
                        ->schema([
                            TextInput::make('name')->label('Produktname')->required(),
                            TextInput::make('type')->label('Art')->placeholder('hoodie, tasse, …'),
                            TextInput::make('price')->label('Preis')->numeric()->prefix('€'),
                            Select::make('availability')
                                ->label('Verfügbarkeit')
                                ->options([
                                    'InStock' => 'Auf Lager',
                                    'OutOfStock' => 'Nicht verfügbar',
                                    'PreOrder' => 'Vorbestellbar',
                                ])
                                ->default('InStock'),
                            TextInput::make('shop_url')->label('Link zum Shop')->url()->columnSpanFull(),
                            FileUpload::make('image')
                                ->label('Produktbild')
                                ->image()
                                ->disk('inhalte')
                                ->directory('uploads/shop')
                                ->columnSpanFull(),
                        ]),
                ]),
        ]);
    }
}
