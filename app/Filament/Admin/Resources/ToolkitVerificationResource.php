<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ToolkitVerificationResource\Pages;
use App\Models\ToolkitVerification;
use App\Models\Participant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ToolkitVerificationResource extends Resource
{
    protected static ?string $model = ToolkitVerification::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $title = 'Monthly Spot Checks';
    protected static ?string $navigationLabel = 'Monthly Spot Checks';
    protected static ?string $navigationGroup = 'Beneficiary Management';
    protected static ?string $modelLabel = 'Spot Check';
    protected static ?string $pluralModelLabel = 'Spot Checks';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Basic Information Section
            Forms\Components\Section::make('Visit Information')
                ->description('Basic details about this spot check visit')
                ->icon('heroicon-o-calendar-days')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('participant_id')
                            ->label('Recipient')
                            ->options(function () {
                                // Get the authenticated user's region name
                            $regionName = auth()->user()->region?->name;

                            // Filter participants by both their award status AND their region
                            return Participant::whereHas('TVET.participant_result', function ($query) {
                                $query->where('status', 'Awarded');
                            })
                            // Add the region filter here!
                            ->when($regionName, function ($query) use ($regionName) {
                                $query->where('region', $regionName);
                            })
                            ->with(['user', 'TVET.participant_result'])
                            ->get()
                            ->mapWithKeys(function ($participant) {
                                $name = $participant->user->name ?? 'Unknown';
                                $idNo = $participant->identity_number ?? 'N/A';
                                return [$participant->id => "{$name} ({$idNo})"];
                            });
                        })
                        ->searchable()
                        ->required()
                        ->columnSpan(1),

                        Forms\Components\DatePicker::make('date_of_visit')
                            ->label('Date of Visit')
                            ->default(now())
                            ->required()
                            ->columnSpan(1),
                    ]),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('number_of_people_met')
                            ->label('Number of People Met')
                            ->numeric()
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('date_of_next_visit')
                            ->label('Date of Next Visit')
                            ->helperText('Schedule the next visit')
                            ->columnSpan(1),
                    ]),

                    Forms\Components\Hidden::make('user_id')
                        ->default(Auth::id()),
                ])
                ->collapsible(),

            // Toolkit Status Section
            Forms\Components\Section::make('Toolkit Status & Delivery')
                ->description('Assessment of toolkit delivery and receipt')
                ->icon('heroicon-o-truck')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Toggle::make('toolkit_received')
                            ->label('Toolkit Received')
                            ->helperText('Has the recipient received their toolkit?')
                            ->reactive()
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('date_toolkit_received')
                            ->label('Date Toolkit Received')
                            ->visible(fn (Forms\Get $get) => $get('toolkit_received') === true)
                            ->columnSpan(1),
                    ]),
                ])
                ->collapsible(),

            // Toolkit Usage Assessment
            Forms\Components\Section::make('Toolkit Usage Assessment')
                ->description('Evaluate how the toolkit is being used')
                ->icon('heroicon-o-wrench-screwdriver')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Toggle::make('is_toolkit_used')
                            ->label('Is toolkit being used?')
                            ->helperText('Is the recipient actively using the toolkit?')
                            ->reactive()
                            ->columnSpan(1),

                        Forms\Components\Select::make('toolkit_usage_frequency')
                            ->label('Toolkit Usage Frequency')
                            ->options([
                                'daily' => 'Daily',
                                'weekly' => 'Weekly',
                                'rarely' => 'Rarely',
                                'not_at_all' => 'Not at all',
                            ])
                            ->required()
                            ->columnSpan(1),
                    ]),

                    Forms\Components\Textarea::make('is_toolkit_used_comment')
                        ->label('Toolkit Usage Comments')
                        ->helperText('Provide details about toolkit usage or reasons for non-usage')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            // Tool Condition Assessment
            Forms\Components\Section::make('Tool Condition Assessment')
                ->description('Physical condition and maintenance of tools')
                ->icon('heroicon-o-cog-6-tooth')
                ->schema([
                    Forms\Components\Toggle::make('condition_of_tools')
                        ->label('Are tools in good condition?')
                        ->helperText('Assess the physical condition of the tools')
                        ->reactive(),

                    Forms\Components\Textarea::make('condition_of_tools_comment')
                        ->label('Tool Condition Comments')
                        ->helperText('Describe the condition of tools, any damage, or maintenance needed')
                        ->placeholder('e.g., "Tools are well-maintained, minor wear on handles, needs sharpening..."')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            // Service Delivery Assessment
            Forms\Components\Section::make('Service Delivery Assessment')
                ->description('Assessment of business activities and service provision')
                ->icon('heroicon-o-building-storefront')
                ->schema([
                    Forms\Components\Toggle::make('recipient_providing_services')
                        ->label('Is recipient providing services?')
                        ->helperText('Is the recipient actively offering services to customers?')
                        ->reactive(),

                    Forms\Components\Textarea::make('recipient_providing_services_comment')
                        ->label('Service Provision Comments')
                        ->helperText('Describe what services are being provided and to whom')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('visible_income_activity')
                        ->label('Visible income-generating activity?')
                        ->helperText('Can you see evidence of income generation?')
                        ->reactive(),

                    Forms\Components\Textarea::make('visible_income_activity_comment')
                        ->label('Income Activity Comments')
                        ->helperText('Describe the income-generating activities observed')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            // Income Assessment
            Forms\Components\Section::make('Income Assessment')
                ->description('Evaluation of income generation and financial performance')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Toggle::make('making_income')
                            ->label('Is recipient making income?')
                            ->helperText('Is the recipient earning money from their activities?')
                            ->reactive()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('approximate_income_per_month')
                            ->label('Income/month (E)')
                            ->helperText('Approximate monthly income in Emalangeni')
                            ->numeric()
                            ->prefix('E')
                            ->columnSpan(1),
                    ]),
                ])
                ->collapsible(),

            // Business Support & Scalability Section
            Forms\Components\Section::make('Business Support & Scalability')
                ->description('Information about additional support and business growth')
                ->icon('heroicon-o-building-office-2')
                ->schema([
                    Forms\Components\Toggle::make('received_other_support')
                        ->label('Scalability of the business - Have you received any other form of support?')
                        ->helperText('Ask: "Besides the tools from ENYC, have you received support from other organizations?"')
                        ->reactive()
                        ->inline(),
                        
                    Forms\Components\Textarea::make('support_entity_details')
                        ->label('Support Entity Details')
                        ->helperText('Ask: "Please tell me about the support - what type, from whom, when, how much?" Common entities: YERF, CFI, ESNAU, EU, UNDP, World Bank, ILO')
                        ->placeholder('e.g., "YERF provided seed funding of E15,000 in March 2024 for equipment purchase..."')
                        ->rows(4)
                        ->visible(fn (Forms\Get $get) => $get('received_other_support') == true),
                ])
                ->collapsible(),

            // Development Groups & Networks Section
            Forms\Components\Section::make('Development Groups & Networks')
                ->description('Information about group memberships and affiliations')
                ->icon('heroicon-o-user-group')
                ->schema([
                    Forms\Components\Toggle::make('affiliated_with_dev_groups')
                        ->label('Are you affiliated with other development groups/organizations?')
                        ->helperText('Ask: "Do you belong to any business groups, cooperatives, or development organizations?"')
                        ->reactive()
                        ->inline(),
                        
                    Forms\Components\RichEditor::make('dev_group_details')
                        ->label('Development Group Details')
                        ->helperText('Ask: "Tell me about these groups - what type are they, how have they helped your business?"')
                        ->placeholder('Provide details about:
            • Group/organization name and type (cooperative, association, network, etc.)
            • How they have helped your business
            • Benefits you have received
            • Your role/involvement level')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'bulletList',
                            'orderedList',
                            'undo',
                            'redo',
                        ])
                        ->visible(fn (Forms\Get $get) => $get('affiliated_with_dev_groups') == true),
                ])
                ->collapsible(),

            // Future Planning Section
            Forms\Components\Section::make('Future Planning & Goals')
                ->description('Document the youth\'s plans and aspirations')
                ->icon('heroicon-o-light-bulb')
                ->schema([
                    Forms\Components\RichEditor::make('future_plans_12_months')
                        ->label('Future Plans & Next 12 Months Goals')
                        ->helperText('Ask: "What are your plans for your business in the next 12 months? What goals do you want to achieve?"')
                        ->placeholder('Document the youth\'s:
• Short-term goals (next 3-6 months)
• Medium-term plans (6-12 months)
• Specific targets (sales, expansion, new products/services)
• Challenges they anticipate
• Support they might need')
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'bulletList',
                            'orderedList',
                            'link',
                            'undo',
                            'redo',
                        ])
                        ->required()
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            // Interview & Field Assessment
            Forms\Components\Section::make('Interview & Field Assessment')
                ->description('Detailed interview notes and field observations')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->schema([
                    Forms\Components\Textarea::make('short_interview')
                        ->label('Short Interview')
                        ->helperText('Record key points from your interview with the recipient')
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('summary_of_activities')
                        ->label('Summary of Technical Assistance')
                        ->helperText('Describe any technical assistance or guidance provided during this visit')
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('field_lessons')
                        ->label('Field Lessons Learnt')
                        ->helperText('Document any lessons learned or insights from this visit')
                        ->rows(4)
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            // Visit Documentation
            Forms\Components\Section::make('Visit Documentation')
                ->description('Official documentation and signatures')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('prepared_by')
                            ->label('Prepared by')
                            ->helperText('Name of officer conducting the visit')
                            ->default(Auth::user()->name)
                            ->columnSpan(1),

                        Forms\Components\DatePicker::make('prepared_on')
                            ->label('Prepared on')
                            ->default(now())
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('site_representative')
                        ->label('Site Representative')
                        ->helperText('Name of person found during the visit')
                        ->default(Auth::user()->name)
                        ->columnSpan(1),

                        Forms\Components\DatePicker::make('site_signed_on')
                            ->label('Date Signed')
                            ->helperText('Date when site representative signed')
                            ->columnSpan(1),
                    ]),
                ])
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('youth.user.name')
                    ->label('Recipient')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('youth.region')
                    ->label('Region')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('toolkit_received')
                    ->label('Toolkit Received')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\IconColumn::make('is_toolkit_used')
                    ->label('In Use')
                    ->boolean()
                    ->trueIcon('heroicon-o-wrench-screwdriver')
                    ->falseIcon('heroicon-o-minus-circle'),

                Tables\Columns\IconColumn::make('visible_income_activity')
                    ->label('Income Activity')
                    ->boolean()
                    ->trueIcon('heroicon-o-banknotes')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\IconColumn::make('received_other_support')
                    ->label('Other Support')
                    ->boolean()
                    ->trueIcon('heroicon-o-building-office-2')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\IconColumn::make('affiliated_with_dev_groups')
                    ->label('Group Affiliated')
                    ->boolean()
                    ->trueIcon('heroicon-o-user-group')
                    ->falseIcon('heroicon-o-minus-circle'),

                Tables\Columns\TextColumn::make('date_of_visit')
                    ->label('Visit Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('prepared_by')
                    ->label('Prepared By')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('toolkit_received')
                    ->label('Toolkit Received')
                    ->query(fn (Builder $query): Builder => $query->where('toolkit_received', true)),
                    
                Tables\Filters\Filter::make('has_other_support')
                    ->label('Has Other Support')
                    ->query(fn (Builder $query): Builder => $query->where('received_other_support', true)),
                    
                Tables\Filters\Filter::make('group_affiliated')
                    ->label('Group Affiliated')
                    ->query(fn (Builder $query): Builder => $query->where('affiliated_with_dev_groups', true)),
                    
                Tables\Filters\Filter::make('making_income')
                    ->label('Making Income')
                    ->query(fn (Builder $query): Builder => $query->where('making_income', true)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date_of_visit', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['youth']);

        if (auth()->user()->hasRole('regional_programs_support_officer')) {
            $regionName = auth()->user()->region?->name;

            // The query is correct if the 'region' column exists on the 'participants' table
            $query->whereHas('youth', function ($q) use ($regionName) {
                $q->where('region', $regionName);
            });
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListToolkitVerifications::route('/'),
            'create' => Pages\CreateToolkitVerification::route('/create'),
            'edit' => Pages\EditToolkitVerification::route('/{record}/edit'),
        ];
    }
}