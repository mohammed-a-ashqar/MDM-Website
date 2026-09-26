<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HomePageResource\Pages;
use App\Models\HomePage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HomePageResource extends Resource
{
    protected static ?string $model = HomePage::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Site';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Home page';

    protected static ?string $modelLabel = 'Home page';

    protected static ?string $pluralModelLabel = 'Home page';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        $card = function (int $n): Forms\Components\Fieldset {
            return Forms\Components\Fieldset::make("Banner column {$n} (3-up row)")
                ->schema([
                    Forms\Components\Textarea::make("section2_card_{$n}_title")
                        ->label('Title')
                        ->rows(2)
                        ->helperText('HTML is allowed for line breaks, e.g. Essential<br />Items')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make("section2_card_{$n}_link_label")
                        ->label('Link text')
                        ->maxLength(255),
                    Forms\Components\TextInput::make("section2_card_{$n}_link_url")
                        ->label('Link URL')
                        ->maxLength(2048),
                    Forms\Components\FileUpload::make("section2_card_{$n}_image_light")
                        ->label('Image (light theme)')
                        ->image()
                        ->disk('public')
                        ->directory('home/section2'),
                    Forms\Components\FileUpload::make("section2_card_{$n}_image_dark")
                        ->label('Image (dark theme)')
                        ->image()
                        ->disk('public')
                        ->directory('home/section2'),
                ])
                ->columns(2);
        };

        return $form
            ->schema([
                Forms\Components\Section::make('Second section — three banners')
                    ->description('Images and text for the three cards under the hero.')
                    ->schema([
                        $card(1),
                        $card(2),
                        $card(3),
                    ]),

                Forms\Components\Section::make('Science & aesthetics section')
                    ->description('“Science That Defines Aesthetics” — hero text + images + feature grid (first 3 visible; rest expand with Show more).')
                    ->schema([
                        Forms\Components\Textarea::make('science_heading')
                            ->label('Heading')
                            ->rows(2)
                            ->helperText('Optional line breaks: use &lt;br&gt; or a single line.')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('science_body')
                            ->label('Body')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('science_button_label')
                            ->label('Button text')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('science_button_url')
                            ->label('Button URL')
                            ->maxLength(2048),
                        Forms\Components\FileUpload::make('science_image_main')
                            ->label('Main image (large, laboratory)')
                            ->image()
                            ->disk('public')
                            ->directory('home/science'),
                        Forms\Components\TextInput::make('science_image_main_alt')
                            ->label('Main image alt text')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('science_image_overlay')
                            ->label('Overlay image (optional, smaller top-right)')
                            ->image()
                            ->disk('public')
                            ->directory('home/science'),
                        Forms\Components\TextInput::make('science_image_overlay_alt')
                            ->label('Overlay image alt text')
                            ->maxLength(255),
                        Forms\Components\Repeater::make('science_features')
                            ->label('Feature columns')
                            ->defaultItems(6)
                            ->schema([
                                Forms\Components\FileUpload::make('icon_image')
                                    ->label('Custom icon (optional)')
                                    ->helperText('Leave empty to show the built-in numbered ring (CSS). Upload only if you want a custom image instead.')
                                    ->image()
                                    ->disk('public')
                                    ->directory('home/science/icons'),
                                Forms\Components\TextInput::make('title')
                                    ->label('Title')
                                    ->maxLength(255)
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('body')
                                    ->label('Description')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Clinical Innovation section')
                    ->description('Two cards: left column highlights (primary background); right column is light. Heading appears beside the cards on large screens.')
                    ->schema([
                        Forms\Components\Textarea::make('clinical_heading')
                            ->label('Section heading')
                            ->rows(2)
                            ->helperText('HTML allowed, e.g. Clinical Innovation<br>for Professionals')
                            ->columnSpanFull(),
                        Forms\Components\Fieldset::make('Card 1 — primary / dark panel (e.g. For Dermatologists)')
                            ->schema([
                                Forms\Components\TextInput::make('clinical_card_1_badge')
                                    ->label('Badge')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('clinical_card_1_title')
                                    ->label('Title')
                                    ->rows(2)
                                    ->helperText('HTML allowed for line breaks')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('clinical_card_1_body')
                                    ->label('Body')
                                    ->rows(5)
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('clinical_card_1_image')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('home/clinical'),
                                Forms\Components\TextInput::make('clinical_card_1_image_alt')
                                    ->label('Image alt text')
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                        Forms\Components\Fieldset::make('Card 2 — light panel (e.g. For Clinics)')
                            ->schema([
                                Forms\Components\TextInput::make('clinical_card_2_badge')
                                    ->label('Badge')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('clinical_card_2_title')
                                    ->label('Title')
                                    ->rows(2)
                                    ->helperText('HTML allowed for line breaks')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('clinical_card_2_body')
                                    ->label('Body')
                                    ->rows(5)
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('clinical_card_2_image')
                                    ->label('Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('home/clinical'),
                                Forms\Components\TextInput::make('clinical_card_2_image_alt')
                                    ->label('Image alt text')
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                    ]),

                Forms\Components\Section::make('Fourth section — special offer + countdown')
                    ->schema([
                        Forms\Components\FileUpload::make('section4_image')
                            ->label('Side image')
                            ->image()
                            ->disk('public')
                            ->directory('home/section4')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('section4_kicker')
                            ->label('Small label')
                            ->maxLength(120),
                        Forms\Components\TextInput::make('section4_badge')
                            ->label('Badge text')
                            ->maxLength(80),
                        Forms\Components\TextInput::make('section4_title')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('section4_description')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('section4_countdown_ends_at')
                            ->label('Countdown end')
                            ->seconds(false),
                        Forms\Components\TextInput::make('section4_button_label')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('section4_button_url')
                            ->label('Button URL')
                            ->maxLength(2048),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Fifth section — split promo')
                    ->schema([
                        Forms\Components\FileUpload::make('section5_image')
                            ->label('Side / background image')
                            ->image()
                            ->disk('public')
                            ->directory('home/section5')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('section5_video_url')
                            ->label('Video URL (YouTube)')
                            ->maxLength(2048)
                            ->helperText('Optional. Shows a play button overlay when set.')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('section5_kicker')
                            ->label('Small label')
                            ->maxLength(120),
                        Forms\Components\TextInput::make('section5_title')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('section5_description')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('section5_button_label')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('section5_button_url')
                            ->label('Button URL')
                            ->maxLength(2048),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Blog block (posts come from Content → Blog)')
                    ->schema([
                        Forms\Components\TextInput::make('blog_section_heading')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('blog_section_intro')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Why Choose Us section')
                    ->description('Homepage “Why Choose Us” block (icons + text).')
                    ->schema([
                        Forms\Components\TextInput::make('choose_us_heading')
                            ->label('Heading')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('choose_us_intro')
                            ->label('Intro text')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('choose_us_items')
                            ->label('Items')
                            ->defaultItems(4)
                            ->schema([
                                Forms\Components\TextInput::make('icon')
                                    ->label('Bootstrap icon class')
                                    ->helperText('Example: bi bi-shield-check')
                                    ->maxLength(120)
                                    ->required(),
                                Forms\Components\TextInput::make('title')
                                    ->label('Title')
                                    ->maxLength(160)
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('body')
                                    ->label('Description')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Partners section')
                    ->description('Homepage “Partners in Success” block (background + logos).')
                    ->schema([
                        Forms\Components\TextInput::make('partners_heading')
                            ->label('Heading')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('partners_intro')
                            ->label('Intro text')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('partners_bg_image')
                            ->label('Background image')
                            ->image()
                            ->disk('public')
                            ->directory('home/partners')
                            ->columnSpanFull(),
                        Forms\Components\Repeater::make('partners_logos')
                            ->label('Partner logos')
                            ->defaultItems(6)
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label('Logo image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('home/partners/logos')
                                    ->required(),
                                Forms\Components\TextInput::make('alt')
                                    ->label('Alt text')
                                    ->maxLength(160)
                                    ->required(),
                                Forms\Components\TextInput::make('url')
                                    ->label('Link (optional)')
                                    ->maxLength(2048),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['alt'] ?? null)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('blog_section_heading')
                    ->label('Blog heading')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->paginated(false)
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomePages::route('/'),
            'edit' => Pages\EditHomePage::route('/{record}/edit'),
        ];
    }
}
