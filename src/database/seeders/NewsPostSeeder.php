<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user and all categories
        $user = User::first();
        if (! $user) {
            $this->command->error('No users found. Please run UserSeeder first.');

            return;
        }

        $categories = Category::all()->keyBy('name');

        // Map category names for article matching
        $categoryMap = [
            'Technology' => ['technology', 'tech', 'digital', 'ai', 'innovation'],
            'Politics' => ['politics', 'government', 'minister', 'king', 'parliament', 'election'],
            'Sports' => ['sports', 'football', 'tennis', 'athletics', 'hockey', 'badminton'],
            'Entertainment' => ['entertainment', 'celebrity', 'movie', 'music', 'showbiz'],
            'Business' => ['business', 'economy', 'market', 'bursa', 'investment', 'finance'],
            'Health' => ['health', 'medical', 'hospital', 'disease', 'covid', 'vaccine', 'wellness'],
        ];

        // Articles from The Star website
        $articles = $this->getStarArticles();

        $created = 0;
        foreach ($articles as $article) {
            try {
                // Determine category based on title and content
                $category = $this->determineCategory($article['title'], $article['content'], $categories, $categoryMap);

                // Generate unique slug
                $baseSlug = Str::slug($article['title']);
                $slug = $baseSlug;
                $counter = 1;
                while (NewsPost::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }

                NewsPost::create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'title' => $article['title'],
                    'slug' => $slug,
                    'content' => $article['content'],
                    'cover_image' => $article['cover_image'] ?? null,
                    'status' => 'published',
                    'views_count' => rand(0, 1000),
                    'likes_count' => rand(0, 100),
                    'published_at' => now()->subDays(rand(0, 30)),
                ]);

                $created++;
                $this->command->info("Created article: {$article['title']}");
            } catch (\Exception $e) {
                $this->command->error("Failed to create article '{$article['title']}': ".$e->getMessage());
            }
        }

        $this->command->info("Successfully created {$created} news articles with published status.");
    }

    /**
     * Determine the appropriate category for an article
     */
    private function determineCategory(string $title, string $content, $categories, array $categoryMap): Category
    {
        $text = strtolower($title.' '.$content);

        foreach ($categoryMap as $categoryName => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    if ($categories->has($categoryName)) {
                        return $categories[$categoryName];
                    }
                }
            }
        }

        // Default to first category if no match
        return $categories->first();
    }

    /**
     * Get articles from The Star website
     */
    private function getStarArticles(): array
    {
        return [
            [
                'title' => "King's historic state visit to Saudi marks new era in bilateral relations",
                'content' => 'RIYADH: The state visit of His Majesty Sultan Ibrahim, King of Malaysia to Saudi Arabia, which concluded Thursday (Nov 6), marks a new chapter of enduring friendship, mutual respect and shared commitment between both nations. The visit has strengthened diplomatic ties and opened new avenues for cooperation in various sectors including trade, investment, and cultural exchange.',
                'cover_image' => null,
            ],
            [
                'title' => 'UPSI bus tragedy: Tour company fined RM20,000 for using illegal permit',
                'content' => 'A tour company has been fined RM20,000 for using an illegal permit in connection with the UPSI bus tragedy. The court found the company guilty of operating without proper authorization, highlighting the importance of regulatory compliance in the tourism industry.',
                'cover_image' => null,
            ],
            [
                'title' => 'Sultan Ibrahim accorded state welcome at Bahrain\'s Sakhir Palace',
                'content' => "His Majesty Sultan Ibrahim, King of Malaysia, was accorded a state welcome at Bahrain's Sakhir Palace during his official visit. The warm reception underscores the strong bilateral relations between Malaysia and Bahrain, with discussions focusing on enhancing cooperation in various fields.",
                'cover_image' => null,
            ],
            [
                'title' => 'JPJ, Immigration act against companies hiring unlicensed foreign drivers',
                'content' => 'The Road Transport Department (JPJ) and Immigration Department have taken action against companies found hiring unlicensed foreign drivers. This crackdown aims to ensure road safety and compliance with Malaysian traffic regulations, protecting both drivers and passengers.',
                'cover_image' => null,
            ],
            [
                'title' => 'Namewee\'s love life in spotlight after longtime girlfriend stands by him as he turns himself in',
                'content' => "Entertainment news: Malaysian rapper Namewee's personal life has come under public scrutiny after his longtime girlfriend publicly supported him as he turned himself in to authorities. The couple's relationship has been a topic of discussion among fans and media alike.",
                'cover_image' => null,
            ],
            [
                'title' => 'Malaysian-born academic appointed UK\'s first Chief Medical, Scientific Officer at MHRA',
                'content' => "A Malaysian-born academic has made history by being appointed as the UK's first Chief Medical and Scientific Officer at the Medicines and Healthcare products Regulatory Agency (MHRA). This appointment reflects the global recognition of Malaysian talent in the medical and scientific fields.",
                'cover_image' => null,
            ],
            [
                'title' => 'QuickCheck: Is it true that JPJ is offering driving licences without tests?',
                'content' => 'FACT CHECK: Rumors circulating about JPJ offering driving licenses without tests are false. The Road Transport Department has clarified that all driving license applications must go through proper testing procedures to ensure road safety standards are maintained.',
                'cover_image' => null,
            ],
            [
                'title' => 'Fire breaks out near Balakong housing area',
                'content' => 'A fire broke out near a housing area in Balakong, causing concern among residents. Fire and rescue services responded quickly to the scene, working to contain the blaze and ensure the safety of nearby residents. Investigations are ongoing to determine the cause of the fire.',
                'cover_image' => null,
            ],
            [
                'title' => 'Turmoil in tiaras at Miss Universe pageant in Thailand; walkout by pageant contestants',
                'content' => 'The Miss Universe pageant in Thailand was marred by controversy as contestants staged a walkout following an incident where one contestant was insulted by an official. The incident has raised questions about the treatment of participants and the need for better oversight in international pageants.',
                'cover_image' => null,
            ],
            [
                'title' => 'The matcha you\'re drinking may harm your kidneys',
                'content' => 'HEALTH ALERT: Recent studies suggest that excessive consumption of certain types of matcha may have adverse effects on kidney health. Health experts recommend moderation and choosing high-quality matcha products from reputable sources to minimize potential health risks.',
                'cover_image' => null,
            ],
            [
                'title' => 'Nepal sends letter to Malaysia, says manpower standards cannot be accepted',
                'content' => 'Nepal has sent an official letter to Malaysia expressing concerns about manpower standards that cannot be accepted. The diplomatic communication highlights ongoing discussions about labor standards and worker protection between the two countries.',
                'cover_image' => null,
            ],
            [
                'title' => 'Four Malaysians arrested for trying to smuggle 86kg of cannabis buds worth RM8.3mil into Britain',
                'content' => 'Four Malaysian nationals have been arrested in Britain for attempting to smuggle 86 kilograms of cannabis buds worth RM8.3 million. The arrests were made following a joint operation between British and Malaysian authorities, demonstrating international cooperation in combating drug trafficking.',
                'cover_image' => null,
            ],
            [
                'title' => 'Rela reminds public against obstructing or abusing on-duty officers',
                'content' => "The People's Volunteer Corps (Rela) has issued a reminder to the public about the consequences of obstructing or abusing officers while they are on duty. The organization emphasizes the importance of respecting law enforcement personnel and cooperating with their work.",
                'cover_image' => null,
            ],
            [
                'title' => 'Sabah polls: Pakatan, BN finalise seat negotiations',
                'content' => 'POLITICS: Pakatan Harapan and Barisan Nasional have finalized their seat negotiations for the upcoming Sabah state elections. The coalition partners have reached an agreement on candidate allocation, setting the stage for a coordinated campaign strategy.',
                'cover_image' => null,
            ],
            [
                'title' => 'Philippine death toll tops 140 as Typhoon Kalmaegi heads towards Vietnam',
                'content' => "The death toll from Typhoon Kalmaegi in the Philippines has exceeded 140 as the storm continues its path towards Vietnam. Emergency services are working around the clock to provide aid to affected communities, while neighboring countries prepare for the storm's arrival.",
                'cover_image' => null,
            ],
            [
                'title' => 'Siti Nurhaliza appointed Adjunct Professor at UiTM',
                'content' => "Malaysian singing sensation Dato' Siti Nurhaliza has been appointed as an Adjunct Professor at Universiti Teknologi MARA (UiTM). The appointment recognizes her contributions to the music industry and her role as a cultural ambassador for Malaysia.",
                'cover_image' => null,
            ],
            [
                'title' => 'Bursa to suspend trading of Pertama Digital shares on Nov 14',
                'content' => 'BUSINESS: Bursa Malaysia has announced that trading of Pertama Digital shares will be suspended on November 14. The suspension comes as part of regulatory measures, with the stock exchange providing details about the reasons and potential implications for shareholders.',
                'cover_image' => null,
            ],
            [
                'title' => 'More than 100,000 technologists, technicians registered under Malaysia Board of Technologists, says Chang',
                'content' => 'TECHNOLOGY: More than 100,000 technologists and technicians have been registered under the Malaysia Board of Technologists, according to Minister Chang. The registration system aims to professionalize the technical workforce and ensure quality standards across various industries.',
                'cover_image' => null,
            ],
            [
                'title' => 'Social Work Profession Bill to regulate private, public sector practitioners, says Nancy',
                'content' => 'The Social Work Profession Bill will regulate practitioners in both private and public sectors, according to Minister Nancy. The legislation aims to establish professional standards and ensure quality social work services across Malaysia, protecting both practitioners and clients.',
                'cover_image' => null,
            ],
            [
                'title' => 'AGC to appeal High Court\'s decisions on Amri, Koh cases',
                'content' => "The Attorney General's Chambers (AGC) has announced its intention to appeal the High Court's decisions in the Amri and Koh cases. The legal proceedings continue as the AGC seeks to challenge the court's rulings through the appellate process.",
                'cover_image' => null,
            ],
            [
                'title' => 'Over 58,400 MM2H passes approved to date',
                'content' => "A total of 782 Malaysia My Second Home (MM2H) passes were approved since the new tier categories were announced last June, bringing the total approved to 58,468. The program continues to attract foreign investors and retirees to Malaysia, contributing to the country's economy.",
                'cover_image' => null,
            ],
            [
                'title' => 'United receives FAA approval for first Starlink-equipped planes',
                'content' => 'TECHNOLOGY: United Airlines announced that the Federal Aviation Administration approved its first Starlink-equipped aircraft type, with the first commercial flight planned for May. This marks a significant advancement in in-flight connectivity technology.',
                'cover_image' => null,
            ],
            [
                'title' => 'Singapore Airlines gets India investment approval for Air India-Vistara merger',
                'content' => 'BUSINESS: Singapore Airlines received Indian government approval for foreign direct investment, clearing a significant hurdle in the merger of Vistara into Air India. The approval paves the way for the consolidation of the two airlines.',
                'cover_image' => null,
            ],
            [
                'title' => 'Teamstar receives Bursa approval for ACE Market listing',
                'content' => "Bursa Malaysia Securities Bhd approved Teamstar Bhd for listing on the ACE Market, with the listing expected by the first quarter of 2026. The approval represents a significant milestone for the company's growth plans.",
                'cover_image' => null,
            ],
            [
                'title' => 'Govt has approved nearly a million applications for foreign workers in five critical sectors',
                'content' => 'The government approved 995,396 foreign worker employment permits in manufacturing, construction, plantation, agriculture, and services sectors. The approvals address labor shortages in critical industries while ensuring proper regulation and worker protection.',
                'cover_image' => null,
            ],
            [
                'title' => 'Health Ministry approves use of Evusheld medication for Covid-19 prevention',
                'content' => 'HEALTH: The Health Ministry approved the use of Evusheld (Tixagevimab and Cilgavimab) for COVID-19 prevention, as announced by Health director-general Tan Sri Dr Noor Hisham Abdullah. The approval provides another tool in the fight against COVID-19.',
                'cover_image' => null,
            ],
            [
                'title' => 'Approval for MRT3 a positive for builders',
                'content' => 'BUSINESS: The approval for the Mass Rapid Transit 3 (MRT3) Circle Line strengthens optimism for the construction sector, with analysts identifying key beneficiaries. The project is expected to create jobs and boost economic activity.',
                'cover_image' => null,
            ],
            [
                'title' => 'Selangor land approved for houses of worship can be withdrawn if not built within stipulated period',
                'content' => 'The Selangor government can withdraw approval for land allocated for non-Muslim houses of worship if not developed within the specified period. This policy ensures that approved land is utilized for its intended purpose in a timely manner.',
                'cover_image' => null,
            ],
            [
                'title' => 'King visits Saudi Arabian Military Industries, briefed on ongoing projects, innovations',
                'content' => 'During his visit to Saudi Arabia, His Majesty Sultan Ibrahim toured the Saudi Arabian Military Industries and was briefed on ongoing projects and innovations. The visit highlights potential areas of defense cooperation between Malaysia and Saudi Arabia.',
                'cover_image' => null,
            ],
            [
                'title' => 'King praises Saudi govt for treating Malaysians working in the country well',
                'content' => 'His Majesty Sultan Ibrahim praised the Saudi government for its treatment of Malaysians working in the country. The recognition acknowledges the positive working conditions and support provided to Malaysian expatriates in Saudi Arabia.',
                'cover_image' => null,
            ],
            [
                'title' => 'King tours Arabian Oud HQ during Riyadh visit',
                'content' => 'His Majesty Sultan Ibrahim toured the Arabian Oud headquarters during his visit to Riyadh. The visit to the luxury fragrance company highlights the cultural and commercial ties between Malaysia and Saudi Arabia.',
                'cover_image' => null,
            ],
            [
                'title' => 'AKPS to recruit military veterans starting Jan next year',
                'content' => 'The Auxiliary Police Force (AKPS) will begin recruiting military veterans starting January next year. The initiative aims to provide employment opportunities for veterans while strengthening the auxiliary police force with experienced personnel.',
                'cover_image' => null,
            ],
            [
                'title' => 'Tennis-Sabalenka overcomes holder Gauff to reach semis of WTA Finals',
                'content' => 'SPORTS: Aryna Sabalenka overcame defending champion Coco Gauff to reach the semifinals of the WTA Finals. The match showcased high-level tennis as Sabalenka advanced in the prestigious tournament.',
                'cover_image' => null,
            ],
            [
                'title' => 'Spain issues fine for AI-generated sexual images of minors',
                'content' => "TECHNOLOGY: Spain has issued fines for AI-generated sexual images of minors, taking a strong stance against the misuse of artificial intelligence technology. The action demonstrates the country's commitment to protecting children in the digital age.",
                'cover_image' => null,
            ],
            [
                'title' => 'Selangor crash out of ACL Two after Persib defeat',
                'content' => "SPORTS: Selangor FC crashed out of the AFC Champions League Two after suffering a defeat to Persib Bandung. The result ends Selangor's campaign in the continental competition.",
                'cover_image' => null,
            ],
            [
                'title' => 'At least seven people trapped after South Korea power plant\'s structure collapsed',
                'content' => 'At least seven people are trapped after a structure collapsed at a power plant in South Korea. Rescue operations are underway as emergency services work to free those trapped in the debris.',
                'cover_image' => null,
            ],
            [
                'title' => 'Soccer-Man United\'s Amorim urges focus on future after Ronaldo criticism',
                'content' => "SPORTS: Manchester United's manager Amorim has urged the team to focus on the future after criticism from Cristiano Ronaldo. The manager emphasized the importance of moving forward and maintaining team unity.",
                'cover_image' => null,
            ],
            [
                'title' => 'Malaysia\'s average household health insurance spending surges 283%',
                'content' => "HEALTH: Malaysia's average household health insurance spending has surged by 283%, reflecting growing awareness of the importance of health coverage. The increase indicates a shift in how Malaysians prioritize their health and financial planning.",
                'cover_image' => null,
            ],
            [
                'title' => 'Dengue cases plummet in Johor, 57% decrease reported in Epi Week 43',
                'content' => 'HEALTH: Johor reported a 57% decrease in dengue cases during Epidemiological Week 43. The significant reduction is attributed to effective vector control measures and public health awareness campaigns.',
                'cover_image' => null,
            ],
            [
                'title' => 'Canada, Malaysia strengthen energy ties: Key investments discussed in bilateral meeting',
                'content' => 'BUSINESS: Canada and Malaysia have strengthened energy ties, discussing key investments during a bilateral meeting. The discussions focused on renewable energy and sustainable development initiatives.',
                'cover_image' => null,
            ],
            [
                'title' => 'Perikatan prepares for Sabah State Election, seat announcement coming soon',
                'content' => 'POLITICS: Perikatan Nasional is preparing for the Sabah State Election, with seat announcements expected soon. The coalition is finalizing its strategy and candidate selection for the upcoming polls.',
                'cover_image' => null,
            ],
            [
                'title' => 'Senior driver mistakenly accelerates into petrol station in Johor, sustains minor injuries',
                'content' => 'A senior driver accidentally accelerated into a petrol station in Johor, sustaining minor injuries. The incident highlights the importance of driver safety and awareness, particularly for elderly drivers.',
                'cover_image' => null,
            ],
            [
                'title' => 'Newborn baby girl found abandoned outside a house in Subang Jaya',
                'content' => 'A newborn baby girl was found abandoned outside a house in Subang Jaya. Authorities are investigating the case and working to ensure the baby receives proper care while searching for the parents.',
                'cover_image' => null,
            ],
            [
                'title' => 'Anwar, Wan Azizah host Asean leaders, spouses at gala dinner',
                'content' => 'POLITICS: Prime Minister Anwar Ibrahim and his wife, Wan Azizah, hosted ASEAN leaders and their spouses at a gala dinner. The event strengthened diplomatic relations and provided an opportunity for regional leaders to engage in informal discussions.',
                'cover_image' => null,
            ],
            [
                'title' => 'Boat capsizes off Indonesia\'s Mentawai islands, 11 people missing',
                'content' => "A boat capsized off Indonesia's Mentawai islands, leaving 11 people missing. Search and rescue operations are ongoing as authorities work to locate the missing individuals.",
                'cover_image' => null,
            ],
            [
                'title' => 'US appeals court temporarily upholds protected status for Afghans',
                'content' => 'A US appeals court temporarily upheld protected status for Afghan nationals. The decision provides temporary relief for Afghan immigrants while legal proceedings continue.',
                'cover_image' => null,
            ],
            [
                'title' => 'Russia says it destroys 55 Ukrainian drones overnight, several people injured',
                'content' => 'Russia reported the destruction of 55 Ukrainian drones overnight, resulting in several injuries. The ongoing conflict continues to impact civilians and infrastructure in the region.',
                'cover_image' => null,
            ],
            [
                'title' => 'Sesame Workshop regains control of Elmo\'s hacked X account after racist posts',
                'content' => "ENTERTAINMENT: Sesame Workshop regained control of Elmo's hacked X account following the posting of racist content. The organization has taken steps to secure the account and prevent future incidents.",
                'cover_image' => null,
            ],
            [
                'title' => 'Analysis-For Europe, 30% US tariff would hammer trade, force export model rethink',
                'content' => 'BUSINESS: An analysis suggests that a 30% US tariff would significantly impact European trade, prompting a reconsideration of export models. The potential tariff could reshape trade relationships and economic strategies.',
                'cover_image' => null,
            ],
            [
                'title' => 'Japan launches government body to address concerns over foreigners',
                'content' => 'Japan has established a government body to address concerns related to foreign residents in the country. The initiative aims to improve integration and address challenges faced by the foreign community.',
                'cover_image' => null,
            ],
            [
                'title' => 'Trump says he is \'disappointed but not done\' with Putin, BBC reports',
                'content' => 'POLITICS: Former President Donald Trump expressed disappointment but indicated he is not finished dealing with Russian President Vladimir Putin, according to BBC reports. The statement comes amid ongoing geopolitical tensions.',
                'cover_image' => null,
            ],
            [
                'title' => 'Japan\'s ruling coalition seen losing upper house majority, polls show',
                'content' => "POLITICS: Polls indicate that Japan's ruling coalition is projected to lose its majority in the upper house. The potential shift could impact the country's political landscape and policy direction.",
                'cover_image' => null,
            ],
            [
                'title' => 'A Florida county leads the way with a high-tech 911 system that improves emergency response',
                'content' => 'TECHNOLOGY: A county in Florida has implemented a high-tech 911 system aimed at improving emergency response times. The advanced system uses modern technology to better coordinate emergency services.',
                'cover_image' => null,
            ],
            [
                'title' => 'US launches new bid to keep migrants detained by denying hearings, memo shows',
                'content' => 'The US government has initiated a new effort to detain migrants by denying them hearings, according to a memo. The policy change has raised concerns about due process and migrant rights.',
                'cover_image' => null,
            ],
        ];
    }
}
