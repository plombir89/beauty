<?php

namespace Database\Seeders;

use App\Models\AboutPageContent;
use App\Models\AboutTeaser;
use App\Models\AboutValue;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BusinessHour;
use App\Models\Certificate;
use App\Models\ContactPolicy;
use App\Models\CtaBlock;
use App\Models\DepositSetting;
use App\Models\ExpertisePillar;
use App\Models\HomeHeroBlock;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SocialLink;
use App\Models\Specialist;
use App\Models\StudioProfile;
use App\Models\WaxingGroup;
use App\Models\WhyChooseUsItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ElegantBeautySeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->seedPages();
            $this->seedStudio();
            $this->seedHomeBlocks();
            $this->seedAbout();
            $this->seedContact();
            $categories = $this->seedServiceCategories();
            $services = $this->seedServices($categories);
            $this->seedWaxing();
            $this->seedSpecialists($services);
            $this->seedBlog();
        });
    }

    protected function seedPages(): void
    {
        $pages = [
            [
                'key' => 'home',
                'title' => ['en' => 'Elegant Beauty Studio', 'ru' => 'Elegant Beauty Studio'],
                'subtitle' => [
                    'en' => 'A place where beauty meets care for your health.',
                    'ru' => 'Место, где красота встречается с заботой о здоровье.',
                ],
                'seo_title' => [
                    'en' => 'Elegant Beauty Studio - Beauty care in Lakewood, WA',
                    'ru' => 'Elegant Beauty Studio - уход за красотой в Лейквуде',
                ],
                'seo_description' => [
                    'en' => 'Certified estheticians, safe methods and individual skin, brows, hair and body treatments in Lakewood, Washington.',
                    'ru' => 'Сертифицированные специалисты, безопасные методы и индивидуальные процедуры для кожи, бровей, волос и тела в Лейквуде.',
                ],
                'og_image' => '/img/9.jpg',
            ],
            [
                'key' => 'services',
                'title' => ['en' => 'Services', 'ru' => 'Услуги'],
                'eyebrow' => ['en' => 'Price list', 'ru' => 'Прайс-лист'],
                'subtitle' => [
                    'en' => 'Facials, treatment programs, brows and lashes, body care and waxing - all performed by certified specialists.',
                    'ru' => 'Уход за лицом, лечебные программы, брови и ресницы, процедуры для тела и депиляция - всё выполняют сертифицированные специалисты.',
                ],
                'seo_title' => ['en' => 'Services - Elegant Beauty Studio', 'ru' => 'Услуги - Elegant Beauty Studio'],
                'seo_description' => [
                    'en' => 'Browse facials, acne programs, brows and lashes, body care, waxing and smile treatments.',
                    'ru' => 'Смотрите уход за лицом, программы акне, брови и ресницы, уход за телом, депиляцию и отбеливание.',
                ],
            ],
            [
                'key' => 'about',
                'title' => ['en' => 'About us', 'ru' => 'О нас'],
                'eyebrow' => ['en' => 'Our story', 'ru' => 'Наша история'],
                'seo_title' => ['en' => 'About us - Elegant Beauty Studio', 'ru' => 'О нас - Elegant Beauty Studio'],
                'seo_description' => [
                    'en' => 'Meet Elegant Beauty Studio and the values behind our care.',
                    'ru' => 'Познакомьтесь с Elegant Beauty Studio и ценностями нашей работы.',
                ],
                'og_image' => '/img/9.jpg',
            ],
            [
                'key' => 'contact',
                'title' => ['en' => 'Book an appointment', 'ru' => 'Записаться на приём'],
                'eyebrow' => ['en' => 'Booking', 'ru' => 'Запись'],
                'subtitle' => [
                    'en' => 'Fill in the form and our specialist will contact you within an hour.',
                    'ru' => 'Заполните форму, и наш специалист свяжется с вами в течение часа.',
                ],
                'seo_title' => ['en' => 'Book an appointment - Elegant Beauty Studio', 'ru' => 'Записаться - Elegant Beauty Studio'],
                'seo_description' => [
                    'en' => 'Book a beauty appointment at Elegant Beauty Studio in Lakewood, WA.',
                    'ru' => 'Запишитесь на процедуру в Elegant Beauty Studio в Лейквуде.',
                ],
            ],
            [
                'key' => 'blog',
                'title' => ['en' => 'Blog', 'ru' => 'Новости'],
                'subtitle' => [
                    'en' => 'Care advice, studio updates and treatment education.',
                    'ru' => 'Советы по уходу, новости студии и полезные материалы о процедурах.',
                ],
                'seo_title' => ['en' => 'Blog - Elegant Beauty Studio', 'ru' => 'Новости - Elegant Beauty Studio'],
                'seo_description' => [
                    'en' => 'Read care guides and updates from Elegant Beauty Studio.',
                    'ru' => 'Читайте советы по уходу и новости Elegant Beauty Studio.',
                ],
            ],
        ];

        foreach ($pages as $index => $page) {
            Page::query()->updateOrCreate(
                ['key' => $page['key']],
                $page + ['sort_order' => $index + 1, 'is_published' => true],
            );
        }
    }

    protected function seedStudio(): void
    {
        StudioProfile::query()->updateOrCreate(
            ['name' => 'Elegant Beauty Studio'],
            [
                'phone' => '253-844-5804',
                'phone_href' => 'tel:+12538445804',
                'email' => 'elegantbrowsss@gmail.com',
                'address' => '5900 100th St SW S#17B, Lakewood, WA 98499',
                'maps_url' => 'https://www.google.com/maps/search/?api=1&query=5900+100th+St+SW+S%2317B+Lakewood+WA+98499',
                'map_embed_url' => 'https://www.google.com/maps?q=5900+100th+St+SW+S%2317B+Lakewood+WA+98499&output=embed',
                'languages' => ['English', 'Russian', 'Ukrainian', 'Moldovan'],
                'is_active' => true,
            ],
        );

        $socials = [
            ['key' => 'instagram', 'label' => 'Instagram', 'href' => 'https://www.instagram.com/elegantbeautystudi'],
            ['key' => 'facebook', 'label' => 'Facebook', 'href' => 'https://www.facebook.com/profile.php?id=61554590098725'],
            ['key' => 'youtube', 'label' => 'YouTube', 'href' => 'https://www.youtube.com/@elegantbeautystudio'],
            ['key' => 'tiktok', 'label' => 'TikTok', 'href' => 'https://www.tiktok.com/@elegant.beauty.studio'],
        ];

        foreach ($socials as $index => $social) {
            SocialLink::query()->updateOrCreate(
                ['key' => $social['key']],
                $social + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }

        $hours = [
            ['key' => 'mon_fri', 'day_label' => ['en' => 'Mon - Fri', 'ru' => 'Пн - Пт'], 'time_label' => ['en' => '9:00 AM - 5:00 PM', 'ru' => '9:00 - 17:00']],
            ['key' => 'sat', 'day_label' => ['en' => 'Sat', 'ru' => 'Сб'], 'time_label' => ['en' => '11:00 AM - 4:00 PM', 'ru' => '11:00 - 16:00']],
            ['key' => 'sun', 'day_label' => ['en' => 'Sun', 'ru' => 'Вс'], 'time_label' => ['en' => 'Closed', 'ru' => 'Выходной']],
        ];

        foreach ($hours as $index => $hour) {
            BusinessHour::query()->updateOrCreate(
                ['key' => $hour['key']],
                $hour + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }

        DepositSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'amount' => 25,
                'currency' => 'USD',
                'payment_methods' => ['Venmo', 'CashApp', 'Zelle', 'PayPal'],
                'offsite_payment_methods' => ['Venmo', 'CashApp', 'Zelle'],
                'eyebrow' => ['en' => 'Secure your slot', 'ru' => 'Закрепить время'],
                'title' => ['en' => 'Booking deposit :amount', 'ru' => 'Депозит за запись :amount'],
                'text' => [
                    'en' => 'The deposit confirms your appointment and is deducted from the price of the procedure. Pay it with PayPal or send it via :methods.',
                    'ru' => 'Депозит подтверждает вашу запись и вычитается из стоимости процедуры. Оплатите его через PayPal или отправьте через :methods.',
                ],
                'note' => [
                    'en' => 'The deposit is non-refundable, but it moves with you if you reschedule at least 48 hours in advance.',
                    'ru' => 'Депозит не возвращается, но переносится вместе с записью, если предупредить не менее чем за 48 часов.',
                ],
                'is_active' => true,
            ],
        );
    }

    protected function seedHomeBlocks(): void
    {
        HomeHeroBlock::query()->updateOrCreate(
            ['key' => 'home'],
            [
                'eyebrow' => ['en' => 'Lakewood, Washington', 'ru' => 'Лейквуд, Вашингтон'],
                'title' => ['en' => 'Elegant Beauty Studio', 'ru' => 'Elegant Beauty Studio'],
                'lead' => [
                    'en' => 'A place where beauty meets care for your health.',
                    'ru' => 'Место, где красота встречается с заботой о здоровье.',
                ],
                'text' => [
                    'en' => 'Certified estheticians, safe and proven methods, and an individual approach to every guest - from facial care to hair and body treatments.',
                    'ru' => 'Сертифицированные специалисты, безопасные и проверенные методы и индивидуальный подход к каждому гостю - от ухода за лицом до процедур для волос и тела.',
                ],
                'primary_label' => ['en' => 'Book an appointment', 'ru' => 'Записаться на приём'],
                'secondary_label' => ['en' => 'Explore services', 'ru' => 'Смотреть услуги'],
                'stats' => [
                    'en' => [
                        ['value' => '18+', 'label' => 'Treatments'],
                        ['value' => '4', 'label' => 'Languages spoken'],
                        ['value' => '1h', 'label' => 'Reply within an hour'],
                    ],
                    'ru' => [
                        ['value' => '18+', 'label' => 'Процедур'],
                        ['value' => '4', 'label' => 'Языка общения'],
                        ['value' => '1h', 'label' => 'Ответ в течение часа'],
                    ],
                ],
                'image' => '/img/9.jpg',
                'is_active' => true,
            ],
        );

        $expertise = [
            [
                'key' => 'areas',
                'icon' => 'heart-handshake',
                'image' => '/img/home/branding.jpg',
                'title' => ['en' => 'Areas of expertise', 'ru' => 'Направления работы'],
                'intro' => [
                    'en' => 'Our team holds in-depth knowledge, experience and skills in a number of specialised fields.',
                    'ru' => 'Команда студии обладает углублёнными знаниями, опытом и навыками в ряде специализированных сфер.',
                ],
                'items' => [
                    'en' => [
                        'Trichology and hair treatment',
                        'Anti-ageing skin care - rejuvenating procedures, peels, RF-lifting',
                        'Hardware cosmetology',
                        'Natural and organic procedures - safe, environmentally friendly care',
                        'Working with sensitive skin',
                        'The ProAcne program - an individual treatment and remission plan based on diagnostics',
                    ],
                    'ru' => [
                        'Трихология и лечение волос',
                        'Антивозрастной уход - омолаживающие процедуры, пилинги, RF-лифтинг',
                        'Аппаратная косметология',
                        'Натуральные и органические процедуры - экологичный и безопасный уход',
                        'Работа с чувствительной кожей',
                        'Программа ProAcne - индивидуальный план лечения и ремиссии акне на основе диагностики',
                    ],
                ],
                'note' => [
                    'en' => 'Our first consultations are often paid, because they include diagnostics - an important part of the result.',
                    'ru' => 'Наши первые консультации часто платные: они включают диагностику - важную часть результата.',
                ],
            ],
            [
                'key' => 'communication',
                'icon' => 'messages-square',
                'image' => '/img/home/communication.jpg',
                'title' => ['en' => 'Communication', 'ru' => 'Коммуникация'],
                'intro' => [
                    'en' => 'We build trusting relationships with every client - from the first call to follow-up care.',
                    'ru' => 'Мы выстраиваем доверительные отношения с каждым клиентом - от первого звонка до последующего ухода.',
                ],
                'items' => [
                    'en' => [
                        'A personalised approach to each and every client',
                        'Answers within an hour - WhatsApp, email, social media or a call',
                        'Multilingual support: English, Russian, Ukrainian and Moldovan',
                        'Online booking, text reminders and reminder calls',
                        '90% of the changes in our service come from real client requests',
                    ],
                    'ru' => [
                        'Персонализированный подход к каждому клиенту',
                        'Ответ в течение часа - WhatsApp, e-mail, соцсети или звонок',
                        'Мультиязычная поддержка: английский, русский, украинский и молдавский',
                        'Онлайн-запись, СМС и звонки-напоминания',
                        '90% изменений в нашем сервисе основаны на реальных пожеланиях клиентов',
                    ],
                ],
                'note' => ['en' => 'We do not just collect feedback - we act on it.', 'ru' => 'Мы не просто собираем отзывы - мы действуем по ним.'],
            ],
            [
                'key' => 'strategy',
                'icon' => 'target',
                'image' => '/img/home/strategy.jpg',
                'title' => ['en' => 'Strategy & values', 'ru' => 'Стратегия и ценности'],
                'intro' => [
                    'en' => 'Our strategy exists to help people gain confidence in themselves. Our values are respect, honesty, support and professionalism.',
                    'ru' => 'Наша стратегия - помочь людям обрести уверенность в себе. Наши ценности: уважение, честность, поддержка и профессионализм.',
                ],
                'items' => [
                    'en' => [
                        'We welcome men and women aged 18 to 100',
                        'Everyone should leave feeling more confident, more beautiful and healthier',
                        'HIPAA privacy of personal information is an absolute priority',
                        'Quick response, sensitivity, openness and education for every client',
                    ],
                    'ru' => [
                        'Мы принимаем мужчин и женщин в возрасте от 18 до 100 лет',
                        'Каждый должен уходить более уверенным, красивым и здоровым',
                        'Конфиденциальность личной информации (HIPAA) - абсолютный приоритет',
                        'Быстрая реакция, чуткость, открытость и обучение каждого клиента',
                    ],
                ],
                'note' => ['en' => 'Reliability you can feel from the very first visit.', 'ru' => 'Надёжность, которую чувствуешь с первого визита.'],
            ],
        ];

        foreach ($expertise as $index => $pillar) {
            ExpertisePillar::query()->updateOrCreate(
                ['key' => $pillar['key']],
                $pillar + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }

        $whyChooseUs = [
            [
                'key' => 'team',
                'slug' => ['en' => 'professional-team', 'ru' => 'professionalnaya-komanda'],
                'icon' => 'sparkles',
                'image' => '/img/services/mensFacialWax.jpg',
                'title' => ['en' => 'Professional team', 'ru' => 'Профессиональная команда'],
                'summary' => [
                    'en' => 'Elegant Beauty Studio employs a team that is passionate about its work.',
                    'ru' => 'В Elegant Beauty Studio работает команда, преданная своему делу.',
                ],
                'body' => [
                    'en' => [
                        'Elegant Beauty Studio employs a team that is passionate about its work. Each specialist has real experience and keeps improving to give you the highest level of service.',
                        'We combine technical skill with sincere care, so each visit feels precise, calm and personal.',
                    ],
                    'ru' => [
                        'В Elegant Beauty Studio работает команда, преданная своему делу. У каждого специалиста есть реальный опыт, и все постоянно совершенствуются, чтобы оказывать услуги на высшем уровне.',
                        'Мы соединяем профессиональные навыки с искренней заботой, чтобы каждый визит был точным, спокойным и персональным.',
                    ],
                ],
            ],
            [
                'key' => 'products',
                'slug' => ['en' => 'premium-products', 'ru' => 'premium-produkty'],
                'icon' => 'shield-check',
                'image' => '/img/home/products.jpg',
                'title' => ['en' => 'Premium products', 'ru' => 'Премиум-продукты'],
                'summary' => [
                    'en' => 'We work with high-quality products that offer exceptional results and additional care.',
                    'ru' => 'Мы работаем с продуктами высокого качества: исключительный результат и дополнительный уход.',
                ],
                'body' => [
                    'en' => [
                        'We work with high-quality products that offer exceptional results, unique formulations and additional care that standard products simply cannot provide.',
                        'Products are selected around skin condition, sensitivity and the treatment goal, not around one fixed protocol.',
                    ],
                    'ru' => [
                        'Мы работаем с продуктами высокого качества: исключительный результат, уникальные формулы и дополнительный уход, который недоступен стандартным средствам.',
                        'Средства подбираются под состояние кожи, чувствительность и цель процедуры, а не под один универсальный протокол.',
                    ],
                ],
            ],
            [
                'key' => 'atmosphere',
                'slug' => ['en' => 'atmosphere', 'ru' => 'atmosfera'],
                'icon' => 'heart-handshake',
                'image' => '/img/home/atmosphere.jpg',
                'title' => ['en' => 'Atmosphere', 'ru' => 'Атмосфера'],
                'summary' => [
                    'en' => 'Soft colours, natural materials, warm lighting, light aromas and calm music.',
                    'ru' => 'Мягкие тона, природные материалы, тёплый свет, лёгкие ароматы и спокойная музыка.',
                ],
                'body' => [
                    'en' => [
                        'Soft colours, natural materials, warm lighting, light aromas and calm music. We pay attention to the details so that every client feels completely at home.',
                        'The space is designed to feel private, quiet and supportive before, during and after the procedure.',
                    ],
                    'ru' => [
                        'Мягкие тона, природные материалы, тёплый свет, лёгкие ароматы и спокойная музыка. Мы продумываем детали, чтобы каждый гость чувствовал себя как дома.',
                        'Пространство создано так, чтобы до, во время и после процедуры было спокойно, приватно и комфортно.',
                    ],
                ],
            ],
        ];

        foreach ($whyChooseUs as $index => $item) {
            WhyChooseUsItem::query()->updateOrCreate(
                ['key' => $item['key']],
                $item + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }

        AboutTeaser::query()->updateOrCreate(
            ['key' => 'home'],
            [
                'eyebrow' => ['en' => 'Who we are', 'ru' => 'Кто мы'],
                'title' => [
                    'en' => 'Care, knowledge and a space that feels safe',
                    'ru' => 'Забота, знания и пространство, где спокойно',
                ],
                'text' => [
                    'en' => 'We are a team that strives to help others using our knowledge and experience. We value honesty and openness, and we keep developing so that everyone who contacts us feels supported. Our goal is to create a space where everyone can find a solution.',
                    'ru' => 'Мы - команда, которая стремится помогать людям, используя свои знания и опыт. Мы ценим честность и открытость и постоянно развиваемся, чтобы каждый, кто к нам обращается, чувствовал поддержку. Наша цель - создать пространство, где каждый найдёт решение.',
                ],
                'cta_label' => ['en' => 'Read our story', 'ru' => 'Наша история'],
                'image' => '/img/title-img.png',
                'is_active' => true,
            ],
        );

        $certificates = [
            '/img/sertificate.jpg',
            '/img/sertificate1.jpg',
            '/img/sertificate2.jpg',
            '/img/sertificate3.jpg',
            '/img/sertificate4.jpg',
            '/img/sertificate5.jpg',
        ];

        foreach ($certificates as $index => $image) {
            Certificate::query()->updateOrCreate(
                ['image' => $image],
                [
                    'title' => ['en' => 'Certificate '.($index + 1), 'ru' => 'Сертификат '.($index + 1)],
                    'alt' => ['en' => 'Elegant Beauty Studio certificate', 'ru' => 'Сертификат Elegant Beauty Studio'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }

        CtaBlock::query()->updateOrCreate(
            ['key' => 'main'],
            [
                'title' => [
                    'en' => 'Book your appointment now and take the first step toward a more confident, beautiful you.',
                    'ru' => 'Запишитесь на приём сейчас и сделайте первый шаг к более уверенной и красивой себе.',
                ],
                'text' => [
                    'en' => 'Book your appointment now and take the first step toward a more confident, beautiful you.',
                    'ru' => 'Запишитесь на приём сейчас и сделайте первый шаг к более уверенной и красивой себе.',
                ],
                'primary_label' => ['en' => 'Book an appointment', 'ru' => 'Записаться на приём'],
                'secondary_label' => ['en' => 'Call :phone', 'ru' => 'Позвонить :phone'],
                'is_active' => true,
            ],
        );
    }

    protected function seedAbout(): void
    {
        AboutPageContent::query()->updateOrCreate(
            ['key' => 'main'],
            [
                'eyebrow' => ['en' => 'Our story', 'ru' => 'Наша история'],
                'title' => ['en' => 'About us', 'ru' => 'О нас'],
                'lead' => [
                    'en' => 'Elegant Beauty Studio is a team that strives to offer the best solutions to those who need us.',
                    'ru' => 'Elegant Beauty Studio - команда, которая стремится предложить лучшие решения тем, кто в нас нуждается.',
                ],
                'text' => [
                    'en' => 'We believe in an individual approach and high quality standards to help you achieve your goals. Studying at the Moscow School of Esthetics enriched me not only with knowledge, but also with a deep understanding of culture and traditions - it became an important part of my professional and personal path.',
                    'ru' => 'Мы верим в индивидуальный подход и высокие стандарты качества, чтобы помочь вам достичь ваших целей. Обучение в Московской школе эстетики обогатило меня не только знаниями, но и глубоким пониманием культуры и традиций - это стало важной частью моего профессионального и личного пути.',
                ],
                'text2' => [
                    'en' => 'I opened my studio after facing the problem of acne with my daughter. That experience was very difficult for us, and I realised how important it is to have access to quality support. Completing the New York Pro Acne qualification with specialists from Russia, Belarus and America gave me the ability to approach this issue professionally. Now my goal is to help other people going through similar difficulties.',
                    'ru' => 'Я открыла свою студию после того, как столкнулась с проблемой акне у дочери. Этот опыт был очень трудным для нас, и я поняла, как важно иметь доступ к качественной поддержке. Пройдя квалификацию по программе New York Pro Acne у специалистов из России, Белоруссии и Америки, я получила возможность профессионально подходить к этой проблеме. Теперь моя цель - помогать другим людям, проходящим через похожие трудности.',
                ],
                'image' => '/img/9.jpg',
                'is_active' => true,
            ],
        );

        $values = [
            ['key' => 'honesty', 'icon' => 'heart-handshake', 'title' => ['en' => 'Honesty', 'ru' => 'Честность'], 'text' => ['en' => 'A realistic plan and a clear price, agreed before we start.', 'ru' => 'Реалистичный план и понятная цена, согласованные до начала работы.']],
            ['key' => 'safety', 'icon' => 'shield-check', 'title' => ['en' => 'Safety', 'ru' => 'Безопасность'], 'text' => ['en' => 'Certified protocols, sterile tools and HIPAA-level privacy.', 'ru' => 'Сертифицированные протоколы, стерильные инструменты и приватность уровня HIPAA.']],
            ['key' => 'care', 'icon' => 'sparkles', 'title' => ['en' => 'Care', 'ru' => 'Забота'], 'text' => ['en' => 'Support between visits, not only during the appointment.', 'ru' => 'Поддержка между визитами, а не только во время процедуры.']],
        ];

        foreach ($values as $index => $value) {
            AboutValue::query()->updateOrCreate(
                ['key' => $value['key']],
                $value + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }

    protected function seedContact(): void
    {
        $policy = ContactPolicy::query()->updateOrCreate(
            ['key' => 'main'],
            [
                'title' => ['en' => 'No-show and late arrival policy', 'ru' => 'Правила отмены и опоздания'],
                'intro' => [
                    'en' => 'We value your time and strive to provide the best care for all our patients. To keep the schedule fair for everyone, please note:',
                    'ru' => 'Мы ценим ваше время и стремимся обеспечить лучший уход для всех клиентов. Чтобы расписание было честным для каждого, обратите внимание:',
                ],
                'kids_note' => ['en' => 'Kids and pets are not allowed in the studio.', 'ru' => 'Вход с детьми и животными в студию запрещён.'],
                'thanks_note' => ['en' => 'Thank you for your understanding and cooperation.', 'ru' => 'Спасибо за понимание и сотрудничество.'],
                'is_active' => true,
            ],
        );

        $policy->items()->delete();

        $items = [
            [
                'key' => 'appointments',
                'icon' => 'calendar-clock',
                'title' => ['en' => 'Appointments', 'ru' => 'Запись'],
                'text' => [
                    'en' => 'A non-refundable $25 deposit secures your appointment and is deducted from the price of the procedure. It can be sent via Venmo, CashApp, Zelle or PayPal. Please arrive on time - if you are more than 15 minutes late we may need to reschedule.',
                    'ru' => 'Невозвратный депозит $25 закрепляет за вами время и вычитается из стоимости процедуры. Оплатить можно через Venmo, CashApp, Zelle или PayPal. Пожалуйста, приходите вовремя - при опоздании более чем на 15 минут приём, возможно, придётся перенести.',
                ],
            ],
            [
                'key' => 'no_show',
                'icon' => 'calendar-x',
                'title' => ['en' => 'No-show', 'ru' => 'Неявка'],
                'text' => [
                    'en' => 'If you miss an appointment without prior notice, a $25 fee may be charged.',
                    'ru' => 'Если вы пропустите приём без предварительного уведомления, может взиматься плата $25.',
                ],
            ],
            [
                'key' => 'cancellation',
                'icon' => 'alert-triangle',
                'title' => ['en' => 'Cancellations', 'ru' => 'Отмена'],
                'text' => [
                    'en' => 'Please give at least 48 hours notice if you need to cancel or reschedule your appointment.',
                    'ru' => 'Пожалуйста, сообщайте об отмене или переносе не менее чем за 48 часов.',
                ],
            ],
        ];

        foreach ($items as $index => $item) {
            $policy->items()->create($item + ['sort_order' => $index + 1, 'is_active' => true]);
        }
    }

    /**
     * @return array<string, ServiceCategory>
     */
    protected function seedServiceCategories(): array
    {
        $categories = [
            ['key' => 'facial', 'slug' => ['en' => 'facials', 'ru' => 'litso'], 'title' => ['en' => 'Facials', 'ru' => 'Лицо']],
            ['key' => 'programs', 'slug' => ['en' => 'programs', 'ru' => 'programmy'], 'title' => ['en' => 'Programs', 'ru' => 'Программы']],
            ['key' => 'brows', 'slug' => ['en' => 'brows-lashes', 'ru' => 'brovi-resnicy'], 'title' => ['en' => 'Brows & lashes', 'ru' => 'Брови и ресницы']],
            ['key' => 'body', 'slug' => ['en' => 'body', 'ru' => 'telo'], 'title' => ['en' => 'Body', 'ru' => 'Тело']],
            ['key' => 'hair', 'slug' => ['en' => 'hair-scalp', 'ru' => 'volosy-kozha-golovy'], 'title' => ['en' => 'Hair & scalp', 'ru' => 'Волосы и кожа головы']],
            ['key' => 'smile', 'slug' => ['en' => 'smile', 'ru' => 'ulybka'], 'title' => ['en' => 'Smile', 'ru' => 'Улыбка']],
        ];

        $models = [];

        foreach ($categories as $index => $category) {
            $models[$category['key']] = ServiceCategory::query()->updateOrCreate(
                ['key' => $category['key']],
                $category + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }

        return $models;
    }

    /**
     * @param  array<string, ServiceCategory>  $categories
     * @return array<string, Service>
     */
    protected function seedServices(array $categories): array
    {
        $allSkinTypes = ['en' => 'All skin types', 'ru' => 'Подходит для всех типов кожи'];
        $oilyCombination = ['en' => 'Oily and combination skin', 'ru' => 'Жирная и комбинированная кожа'];

        $services = [
            [
                'key' => 'deep-pore-cleaning',
                'category' => 'facial',
                'image' => '/img/services/europeanDeepPoreCleansing.jpg',
                'featured' => 1,
                'title' => ['en' => 'Deep pore cleaning', 'ru' => 'Глубокая очистка пор'],
                'display_price' => ['en' => '$60.99', 'ru' => '$60.99'],
                'price_from' => 60.99,
                'summary' => ['en' => 'A European-style cleansing facial that clears congested pores and calms breakouts.', 'ru' => 'Европейская чистка, которая освобождает забитые поры и успокаивает высыпания.'],
                'benefits' => [
                    'en' => ['Cleanses pores and removes excess sebum', 'Prevents inflammation and acne', 'Exfoliates and reduces breakouts', 'Leaves the skin visibly smoother'],
                    'ru' => ['Очищает поры и удаляет излишки кожного сала', 'Предотвращает воспаления и появление акне', 'Эксфолиация и снижение высыпаний', 'Кожа становится заметно более гладкой'],
                ],
                'skin_type' => $oilyCombination,
                'prices' => [['amount' => 60.99]],
            ],
            [
                'key' => 'facial-chemical-peels',
                'category' => 'facial',
                'image' => '/img/services/facialPillungs.jpg',
                'title' => ['en' => 'Facial chemical peels', 'ru' => 'Химические пилинги для лица'],
                'display_price' => ['en' => '$139.99 - $269.99', 'ru' => '$139.99 - $269.99'],
                'price_from' => 139.99,
                'summary' => ['en' => 'Peels selected individually and adjusted to the season for a controlled skin renewal.', 'ru' => 'Пилинги подбираются индивидуально и по сезону - контролируемое обновление кожи.'],
                'benefits' => [
                    'en' => ['Removes dead skin cells', 'Reduces wrinkles and fine lines', 'Helps to fight acne', 'Reduces pigmentation'],
                    'ru' => ['Удаляет омертвевшие клетки кожи', 'Уменьшает морщины и тонкие линии', 'Помогает в борьбе с акне', 'Снижает пигментацию'],
                ],
                'skin_type' => $allSkinTypes,
                'prices' => [['amount' => 139.99, 'amount_max' => 269.99]],
            ],
            [
                'key' => 'hydrodermabrasion',
                'category' => 'facial',
                'image' => '/img/services/hydraFacial.jpg',
                'featured' => 4,
                'title' => ['en' => 'Hydrodermabrasion', 'ru' => 'Гидродермабразия'],
                'display_price' => ['en' => '$109.00', 'ru' => '$109.00'],
                'price_from' => 109,
                'summary' => ['en' => 'Gentle water-based resurfacing that exfoliates and deeply hydrates in one session.', 'ru' => 'Деликатная водная шлифовка: отшелушивание и глубокое увлажнение за одну процедуру.'],
                'benefits' => [
                    'en' => ['Gentle exfoliation removes dead skin cells', 'Hypoallergenic, moisturising solutions restore the skin', 'Stimulates circulation and metabolism', 'Supports natural skin renewal'],
                    'ru' => ['Мягкое отшелушивание удаляет омертвевшие клетки', 'Гипоаллергенные увлажняющие растворы восстанавливают кожу', 'Стимулирует кровообращение и обмен веществ', 'Способствует естественному обновлению кожи'],
                ],
                'skin_type' => $allSkinTypes,
                'prices' => [['amount' => 109]],
            ],
            [
                'key' => 'dermaplaning-facial',
                'category' => 'facial',
                'image' => '/img/services/dermaplaningFacial.jpg',
                'title' => ['en' => 'Dermaplaning facial', 'ru' => 'Дермапланинг лица'],
                'display_price' => ['en' => '$119.99', 'ru' => '$119.99'],
                'price_from' => 119.99,
                'summary' => ['en' => 'Removes dead cells and vellus hair so your skincare works the way it should.', 'ru' => 'Удаляет омертвевшие клетки и пушковые волосы, чтобы уход работал в полную силу.'],
                'benefits' => [
                    'en' => ['Removes dead cells and vellus hair', 'Improves absorption of active ingredients', 'Instantly smoother make-up application'],
                    'ru' => ['Удаляет омертвевшие клетки и пушковые волосы', 'Улучшает всасывание питательных веществ', 'Макияж ложится ровнее сразу после процедуры'],
                ],
                'skin_type' => $oilyCombination,
                'prices' => [['amount' => 119.99]],
            ],
            [
                'key' => 'ultrasonic-facial',
                'category' => 'facial',
                'image' => '/img/services/ultrasonicFacial.jpg',
                'title' => ['en' => 'Ultrasonic facial', 'ru' => 'Ультразвуковой уход за лицом'],
                'display_price' => ['en' => '$119.99', 'ru' => '$119.99'],
                'price_from' => 119.99,
                'summary' => ['en' => 'High-frequency ultrasound waves that boost circulation and cell renewal.', 'ru' => 'Ультразвуковые волны высокой частоты усиливают кровообращение и обновление клеток.'],
                'benefits' => [
                    'en' => ['Enhances blood circulation', 'Promotes cell renewal', 'Prevents acne and inflammation', 'General skin rejuvenation'],
                    'ru' => ['Усиливает кровообращение', 'Способствует обновлению клеток', 'Профилактика акне и воспалений', 'Общее омоложение кожи'],
                ],
                'skin_type' => $allSkinTypes,
                'prices' => [['amount' => 119.99]],
            ],
            [
                'key' => 'microneedling',
                'category' => 'facial',
                'image' => '/img/services/microneedling.jpg',
                'featured' => 5,
                'title' => ['en' => 'Microneedling', 'ru' => 'Микронидлинг'],
                'display_price' => ['en' => '$174.99', 'ru' => '$174.99'],
                'price_from' => 174.99,
                'summary' => ['en' => 'Collagen induction therapy that rebuilds skin density from the inside out.', 'ru' => 'Терапия индукции коллагена - плотность кожи восстанавливается изнутри.'],
                'benefits' => [
                    'en' => ['Stimulates deep skin layers for natural rejuvenation', 'Boosts collagen and elastin production', 'Improves elasticity and refines pores', 'Reduces scars and wrinkles'],
                    'ru' => ['Стимулирует глубокие слои кожи для естественного омоложения', 'Усиливает выработку коллагена и эластина', 'Повышает упругость и сокращает поры', 'Уменьшает рубцы и морщины'],
                ],
                'skin_type' => $allSkinTypes,
                'prices' => [['amount' => 174.99]],
            ],
            [
                'key' => 'face-and-decollete-massage',
                'category' => 'facial',
                'image' => '/img/services/faceDecolleteMassage.jpg',
                'title' => ['en' => 'Face, neck and décolleté massage', 'ru' => 'Массаж лица, шеи и зоны декольте'],
                'display_price' => ['en' => '$99.99', 'ru' => '$99.99'],
                'price_from' => 99.99,
                'summary' => ['en' => 'A sculpting, lymph-draining massage that relaxes the face and lifts the contour.', 'ru' => 'Скульптурирующий лимфодренажный массаж: расслабляет лицо и подтягивает контур.'],
                'benefits' => [
                    'en' => ['Improves blood circulation', 'Relaxes muscles and eases migraines', 'Reduces swelling, drains lymph', 'Tightens the facial contour'],
                    'ru' => ['Улучшает циркуляцию крови', 'Расслабляет мышцы, помогает при мигрени', 'Уменьшает отёчность, обеспечивает лимфодренаж', 'Подтягивает контур лица'],
                ],
                'skin_type' => $allSkinTypes,
                'prices' => [['amount' => 99.99]],
            ],
            [
                'key' => 'pro-acne',
                'category' => 'programs',
                'image' => '/img/services/pro-acneProgram.jpg',
                'title' => ['en' => 'Pro-Acne consultation', 'ru' => 'Консультация Pro-Acne'],
                'display_price' => ['en' => "In-clinic consultation - $79.99\nOnline consultation - $79.99", 'ru' => "Консультация в клинике - $79.99\nОнлайн-консультация - $79.99"],
                'price_from' => 79.99,
                'summary' => ['en' => 'The required first step before any acne program: diagnosis, education and a personal plan.', 'ru' => 'Обязательный первый шаг любой программы: диагностика, обучение и личный план.'],
                'benefits' => [
                    'en' => ['Visual and skin analysis to identify the type and grade of acne', 'Assessment of your skin type and current condition', 'Guidance on caring for acne-prone skin', 'Review of every potential acne trigger', 'An individual treatment strategy - in-clinic care plus home skincare'],
                    'ru' => ['Визуальный анализ кожи: тип и степень акне', 'Оценка типа кожи и её текущего состояния', 'Рекомендации по уходу за кожей, склонной к акне', 'Разбор всех возможных причин высыпаний', 'Индивидуальная стратегия: процедуры в клинике плюс домашний уход'],
                ],
                'details' => ['en' => 'A consultation is required before starting the program.', 'ru' => 'Перед началом программы требуется консультация.'],
                'skin_type' => $allSkinTypes,
                'prices' => [
                    ['label' => ['en' => 'In-clinic consultation', 'ru' => 'Консультация в клинике'], 'amount' => 79.99],
                    ['label' => ['en' => 'Online consultation', 'ru' => 'Онлайн-консультация'], 'amount' => 79.99],
                ],
            ],
            [
                'key' => 'mild-acne-program',
                'category' => 'programs',
                'image' => '/img/services/mild-acne.jpg',
                'title' => ['en' => 'Mild acne program', 'ru' => 'Программа лёгкой степени акне'],
                'display_price' => ['en' => '$700', 'ru' => '$700'],
                'price_from' => 700,
                'duration' => ['en' => '3 hours of in-clinic treatments', 'ru' => '3 часа процедур в клинике'],
                'summary' => ['en' => 'Three hours of customised in-clinic care with a full at-home routine.', 'ru' => 'Три часа индивидуальных процедур в клинике и полный домашний уход.'],
                'benefits' => [
                    'en' => ['3 hours of in-clinic customised treatments, scheduled 2-3 weeks apart', 'Manual extractions, chemical peels and LED light therapy', 'Each session lasts 30 minutes or more', 'A set of 10 skincare and non-comedogenic hair care products for home', 'Unlimited text chat with your acne specialist'],
                    'ru' => ['3 часа индивидуальных процедур с интервалом 2-3 недели', 'Ручная экстракция, химические пилинги, LED-терапия', 'Каждая процедура длится от 30 минут', 'Набор из 10 средств для кожи и некомедогенного ухода за волосами', 'Неограниченный текстовый чат со специалистом'],
                ],
                'skin_type' => $allSkinTypes,
                'prices' => [['amount' => 700]],
            ],
            [
                'key' => 'standard-acne-program',
                'category' => 'programs',
                'image' => '/img/services/standart-acne.jpg',
                'title' => ['en' => 'Standard acne program', 'ru' => 'Стандартная программа акне'],
                'display_price' => ['en' => '$1,300', 'ru' => '$1,300'],
                'price_from' => 1300,
                'duration' => ['en' => '6 hours of in-clinic treatments', 'ru' => '6 часов процедур в клинике'],
                'summary' => ['en' => 'Our most complete in-clinic acne protocol with ongoing specialist support.', 'ru' => 'Самый полный клинический протокол лечения акне с постоянной поддержкой.'],
                'benefits' => [
                    'en' => ['6 hours of in-clinic customised treatments, scheduled 2-3 weeks apart', 'Manual extractions, chemical peels and LED light therapy', 'Each session lasts 30 minutes or more', 'A set of 8 skincare and non-comedogenic hair care products for home', 'Unlimited text chat with your acne specialist'],
                    'ru' => ['6 часов индивидуальных процедур с интервалом 2-3 недели', 'Ручная экстракция, химические пилинги, LED-терапия', 'Каждая процедура длится от 30 минут', 'Набор из 8 средств для кожи и некомедогенного ухода за волосами', 'Неограниченный текстовый чат со специалистом'],
                ],
                'skin_type' => $allSkinTypes,
                'prices' => [['amount' => 1300]],
            ],
            [
                'key' => 'virtual-acne-program',
                'category' => 'programs',
                'image' => '/img/services/acne-program.jpg',
                'title' => ['en' => 'Virtual acne program', 'ru' => 'Онлайн-программа лечения акне'],
                'display_price' => ['en' => '$500', 'ru' => '$500'],
                'price_from' => 500,
                'duration' => ['en' => '6 online visits', 'ru' => '6 онлайн-встреч'],
                'summary' => ['en' => 'Clear skin without clinic visits - a fully remote, long-term acne program.', 'ru' => 'Чистая кожа без визитов в клинику - полностью дистанционная программа.'],
                'benefits' => [
                    'en' => ['6 online visits with your acne specialist', 'Education and guided product ordering', 'Tailored to your skin and your lifestyle', 'An effective all-in-one solution wherever you are', 'Unlimited text chat with your specialist'],
                    'ru' => ['6 онлайн-встреч со специалистом', 'Обучение и помощь в подборе и заказе средств', 'Программа адаптируется под вашу кожу и образ жизни', 'Эффективное комплексное решение, где бы вы ни находились', 'Неограниченный текстовый чат со специалистом'],
                ],
                'details' => [
                    'en' => 'We know not everyone can visit us in person. That is why we offer a fully customised, long-term acne program designed to deliver clear skin with no clinic visits required.',
                    'ru' => 'Мы знаем, что не все могут посетить нас лично. Поэтому мы предлагаем полностью индивидуальную долгосрочную программу для чистой кожи - без визитов в клинику.',
                ],
                'skin_type' => $allSkinTypes,
                'prices' => [['amount' => 500]],
            ],
            [
                'key' => 'pigmentation-program',
                'category' => 'programs',
                'image' => '/img/services/pigmentation.jpg',
                'title' => ['en' => 'Pigmentation program', 'ru' => 'Программа против пигментации'],
                'display_price' => ['en' => '$1,050 - $1,400', 'ru' => '$1,050 - $1,400'],
                'price_from' => 1050,
                'summary' => ['en' => 'A brightening protocol for melasma and post-inflammatory hyperpigmentation.', 'ru' => 'Осветляющий протокол при мелазме и поствоспалительной гиперпигментации.'],
                'benefits' => [
                    'en' => ['Mild concern - $1,050: 4 procedures plus microneedling', 'Severe concern - $1,400: 6 procedures plus microneedling', 'Sessions scheduled 1-2 weeks apart, 30 minutes to 1 hour each', 'Includes one serum for your at-home regimen', 'Unlimited text chat with your specialist'],
                    'ru' => ['Лёгкая степень - $1,050: 4 процедуры плюс микронидлинг', 'Тяжёлая степень - $1,400: 6 процедуры плюс микронидлинг', 'Интервал 1-2 недели, каждая процедура 30 минут - 1 час', 'Включена одна сыворотка для домашнего ухода', 'Неограниченный текстовый чат со специалистом'],
                ],
                'details' => [
                    'en' => 'A skincare program focused on depigmentation and brightening, particularly for conditions such as melasma and post-inflammatory hyperpigmentation. It combines in-clinic treatments with a guided at-home routine.',
                    'ru' => 'Программа направлена на депигментацию и осветление кожи, особенно при мелазме и поствоспалительной гиперпигментации. Сочетает процедуры в клинике и домашний уход.',
                ],
                'skin_type' => $allSkinTypes,
                'prices' => [['amount' => 1050, 'amount_max' => 1400]],
            ],
            [
                'key' => 'lamination',
                'category' => 'brows',
                'image' => '/img/services/lamination.jpg',
                'featured' => 2,
                'title' => ['en' => 'Lamination (brows & lashes)', 'ru' => 'Ламинирование (брови и ресницы)'],
                'display_price' => ['en' => '$59.99', 'ru' => '$59.99'],
                'price_from' => 59.99,
                'duration' => ['en' => 'Lasts 4-6 weeks', 'ru' => 'Держится 4-6 недель'],
                'summary' => ['en' => 'Fuller, fluffier brows and a lifted lash line - polished for weeks.', 'ru' => 'Более густые и пушистые брови, приподнятые ресницы - на несколько недель.'],
                'benefits' => [
                    'en' => ['Straightens and lifts the hairs for a fuller, fluffier look', 'Reshapes unruly or thinning brows', 'Covers gaps and uneven growth', 'Long-lasting results - typically 4-6 weeks'],
                    'ru' => ['Выпрямляет и приподнимает волоски, делая их гуще и пушистее', 'Корректирует форму непослушных или редких бровей', 'Скрывает пробелы и неравномерный рост', 'Долгосрочный результат - обычно 4-6 недель'],
                ],
                'prices' => [['amount' => 59.99]],
            ],
            [
                'key' => 'tinting',
                'category' => 'brows',
                'image' => '/img/services/tining.jpg',
                'featured' => 3,
                'title' => ['en' => 'Tinting (brows & lashes)', 'ru' => 'Тинтинг (брови и ресницы)'],
                'display_price' => ['en' => '$59.99', 'ru' => '$59.99'],
                'price_from' => 59.99,
                'duration' => ['en' => 'Lasts 3-6 weeks', 'ru' => 'Держится 3-6 недель'],
                'summary' => ['en' => 'Depth and definition without daily make-up - ideal for light or sparse hairs.', 'ru' => 'Цвет и чёткость без ежедневного макияжа - идеально для светлых или редких волосков.'],
                'benefits' => [
                    'en' => ['Enhances colour and definition without daily make-up', 'Ideal for light or sparse hairs, making them visible', 'Waterproof', 'Saves time in your daily routine', 'Results last around 3-6 weeks'],
                    'ru' => ['Усиливает цвет и чёткость без ежедневного макияжа', 'Подходит для светлых или редких волосков - делает их заметнее', 'Водостойкий', 'Экономит время в повседневной рутине', 'Результат сохраняется примерно 3-6 недель'],
                ],
                'prices' => [['amount' => 59.99]],
            ],
            [
                'key' => 'full-body-massage',
                'category' => 'body',
                'image' => '/img/services/full-body-massage.jpg',
                'featured' => 6,
                'title' => ['en' => 'Full body massage', 'ru' => 'Комплексный массаж тела'],
                'display_price' => ['en' => '$110 - 1 hour', 'ru' => '$110 - 1 час'],
                'price_from' => 110,
                'duration' => ['en' => '1 hour', 'ru' => '1 час'],
                'summary' => ['en' => 'Rehabilitative bodywork with massage specialist Vladimir Chernov.', 'ru' => 'Реабилитационный массаж со специалистом Владимиром Черновым.'],
                'benefits' => [
                    'en' => ['Rehabilitative massage after injuries', 'Improves blood circulation', 'Relieves muscle tension and reduces pain', 'Accelerates tissue regeneration', 'Strengthens the immune system'],
                    'ru' => ['Реабилитационный массаж после травм', 'Улучшает кровообращение', 'Снимает мышечное напряжение и уменьшает боль', 'Ускоряет восстановление тканей', 'Укрепляет иммунитет'],
                ],
                'note' => ['en' => 'Massage specialist - Vladimir Chernov', 'ru' => 'Массажист - Владимир Чернов'],
                'prices' => [['amount' => 110, 'duration' => ['en' => '1 hour', 'ru' => '1 час']]],
            ],
            [
                'key' => 'bandage-body-wrap',
                'category' => 'body',
                'image' => '/img/services/banageBodyWrap.jpg',
                'title' => ['en' => 'Bandage body wrap', 'ru' => 'Бандажное обёртывание тела'],
                'display_price' => ['en' => "Full body wrap - $149.99\nHerbal wrap - $139.99\nSeaweed body wrap - $139.99", 'ru' => "Полное обёртывание тела - $149.99\nТравяное обёртывание - $139.99\nОбёртывание из водорослей - $139.99"],
                'price_from' => 139.99,
                'summary' => ['en' => 'Detoxifying wraps that refine body contours and deeply nourish the skin.', 'ru' => 'Детокс-обёртывания: улучшают контуры тела и глубоко питают кожу.'],
                'benefits' => [
                    'en' => ['Removes toxins and waste from the body', 'Improves blood circulation and lymphatic drainage', 'Reduces swelling and improves body contours', 'Nourishes and moisturises the skin', 'Promotes muscle relaxation and overall well-being'],
                    'ru' => ['Выводит токсины и шлаки из организма', 'Улучшает кровообращение и лимфодренаж', 'Снимает отёки и улучшает контуры тела', 'Питает и увлажняет кожу', 'Способствует расслаблению мышц и общему самочувствию'],
                ],
                'prices' => [
                    ['label' => ['en' => 'Full body wrap', 'ru' => 'Полное обёртывание тела'], 'amount' => 149.99],
                    ['label' => ['en' => 'Herbal wrap', 'ru' => 'Травяное обёртывание'], 'amount' => 139.99],
                    ['label' => ['en' => 'Seaweed body wrap', 'ru' => 'Обёртывание из водорослей'], 'amount' => 139.99],
                ],
            ],
            [
                'key' => 'scalp-needling',
                'category' => 'hair',
                'image' => '/img/services/scalpNeedling.jpg',
                'title' => ['en' => 'Scalp needling / trich needling', 'ru' => 'Микронидлинг кожи головы / трихонидлинг'],
                'display_price' => ['en' => '$174.99', 'ru' => '$174.99'],
                'price_from' => 174.99,
                'summary' => ['en' => 'Wakes up hair follicles, improves density and calms the scalp.', 'ru' => 'Пробуждает волосяные фолликулы, повышает плотность и успокаивает кожу головы.'],
                'benefits' => [
                    'en' => ['Stimulates hair growth', 'Improves nutrition of the hair follicles', 'Activates new hair growth and improves density', 'Combats dandruff, dry scalp and itching', 'Effective for local and hereditary alopecia'],
                    'ru' => ['Стимулирует рост волос', 'Улучшает питание волосяных фолликулов', 'Активирует рост новых волос и повышает густоту', 'Помогает при перхоти, сухости и зуде кожи головы', 'Эффективен при очаговой и наследственной алопеции'],
                ],
                'skin_type' => $allSkinTypes,
                'prices' => [['amount' => 174.99]],
            ],
            [
                'key' => 'teeth-whitening',
                'category' => 'smile',
                'image' => '/img/services/philipsZoomTeethWhitening.jpg',
                'title' => ['en' => 'Philips Zoom teeth whitening', 'ru' => 'Отбеливание зубов Philips Zoom'],
                'display_price' => ['en' => '$249.99 + $49.99', 'ru' => '$249.99 + $49.99'],
                'price_from' => 249.99,
                'duration' => ['en' => 'About 3 hours', 'ru' => 'Около 3 часов'],
                'summary' => ['en' => 'Noticeably whiter teeth in a single visit - take-home supplies included.', 'ru' => 'Заметно белее уже после одного визита - с набором для дома.'],
                'benefits' => [
                    'en' => ['Fast results - teeth become noticeably whiter in one 3-hour procedure', 'A special gel and lamp provide safe whitening', 'Long-lasting effect', 'Minimal discomfort'],
                    'ru' => ['Быстрый результат - заметно белее после одной процедуры (3 часа)', 'Специальный гель и лампа обеспечивают безопасное отбеливание', 'Долговременный эффект', 'Минимальный дискомфорт'],
                ],
                'note' => ['en' => 'Touch-up for returning clients within 6 months - $119.99', 'ru' => 'Коррекция для возвращающихся клиентов в течение 6 месяцев - $119.99'],
                'prices' => [
                    ['label' => ['en' => 'Philips Zoom teeth whitening', 'ru' => 'Отбеливание зубов Philips Zoom'], 'amount' => 249.99],
                    ['label' => ['en' => 'Take-home supplies', 'ru' => 'Набор для дома'], 'amount' => 49.99],
                ],
            ],
        ];

        $models = [];

        foreach ($services as $index => $serviceData) {
            $service = Service::query()->updateOrCreate(
                ['key' => $serviceData['key']],
                [
                    'service_category_id' => $categories[$serviceData['category']]->id,
                    'slug' => [
                        'en' => $serviceData['key'],
                        'ru' => Str::slug($serviceData['title']['ru'], '-', 'ru'),
                    ],
                    'title' => $serviceData['title'],
                    'display_price' => $serviceData['display_price'],
                    'price_from' => $serviceData['price_from'],
                    'duration' => $serviceData['duration'] ?? null,
                    'summary' => $serviceData['summary'],
                    'benefits' => $serviceData['benefits'],
                    'details' => $serviceData['details'] ?? null,
                    'skin_type' => $serviceData['skin_type'] ?? null,
                    'note' => $serviceData['note'] ?? null,
                    'image' => $serviceData['image'],
                    'is_featured' => isset($serviceData['featured']),
                    'featured_sort_order' => $serviceData['featured'] ?? null,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );

            $service->prices()->delete();

            foreach ($serviceData['prices'] as $priceIndex => $price) {
                if (! is_array($price)) {
                    continue;
                }

                $priceData = array_replace([
                    'label' => null,
                    'amount' => null,
                    'amount_max' => null,
                    'display_price' => $serviceData['display_price'],
                    'duration' => null,
                    'note' => null,
                ], $price);

                $service->prices()->create([
                    'label' => $priceData['label'],
                    'amount' => $priceData['amount'],
                    'amount_max' => $priceData['amount_max'],
                    'currency' => 'USD',
                    'display_price' => $priceData['display_price'],
                    'duration' => $priceData['duration'],
                    'note' => $priceData['note'],
                    'sort_order' => $priceIndex + 1,
                    'is_primary' => $priceIndex === 0,
                ]);
            }

            $models[$service->key] = $service;
        }

        return $models;
    }

    protected function seedWaxing(): void
    {
        $waxingCopy = [
            'women' => ['en' => "Women's waxing", 'ru' => 'Женская депиляция'],
            'men' => ['en' => "Men's waxing", 'ru' => 'Мужская депиляция'],
            'menFacial' => ['en' => "Men's facial wax", 'ru' => 'Мужская депиляция лица'],
        ];

        $items = [
            'fullFace' => ['en' => 'Full face', 'ru' => 'Всё лицо'],
            'brows' => ['en' => 'Brows', 'ru' => 'Брови'],
            'chinLip' => ['en' => 'Chin / upper lip', 'ru' => 'Подбородок / верхняя губа'],
            'nostrils' => ['en' => 'Nostrils', 'ru' => 'Ноздри'],
            'ears' => ['en' => 'Ears', 'ru' => 'Уши'],
            'fullArms' => ['en' => 'Full arms', 'ru' => 'Руки полностью'],
            'upperArms' => ['en' => 'Upper arms', 'ru' => 'Верхняя часть рук'],
            'forearms' => ['en' => 'Forearms', 'ru' => 'Предплечья'],
            'armpits' => ['en' => 'Armpits', 'ru' => 'Подмышки'],
            'fullStomach' => ['en' => 'Full stomach', 'ru' => 'Живот полностью'],
            'bikiniClassic' => ['en' => 'Bikini classic', 'ru' => 'Классическое бикини'],
            'brazilian' => ['en' => 'Brazilian', 'ru' => 'Бразильское бикини'],
            'fullLegs' => ['en' => 'Full legs', 'ru' => 'Ноги полностью'],
            'upperLegs' => ['en' => 'Upper legs', 'ru' => 'Верхняя часть ног'],
            'lowerLegs' => ['en' => 'Lower legs', 'ru' => 'Нижняя часть ног'],
            'stomachLine' => ['en' => 'Stomach line', 'ru' => 'Линия на животе'],
            'chest' => ['en' => 'Chest', 'ru' => 'Грудь'],
            'chestStomach' => ['en' => 'Chest + stomach', 'ru' => 'Грудь и живот'],
            'back' => ['en' => 'Back', 'ru' => 'Спина'],
            'lowerBack' => ['en' => 'Lower back', 'ru' => 'Нижняя часть спины'],
            'upperBack' => ['en' => 'Upper back', 'ru' => 'Верхняя часть спины'],
            'fullCombo' => ['en' => 'Beard + brows + ears + nostrils', 'ru' => 'Борода + брови + уши + ноздри'],
            'beardContouring' => ['en' => 'Beard contouring', 'ru' => 'Контур бороды'],
            'browContouring' => ['en' => 'Brow contouring', 'ru' => 'Контур бровей'],
            'cheeks' => ['en' => 'Face cheeks', 'ru' => 'Щёки'],
        ];

        $groups = [
            'women' => [
                ['fullFace', 44.99], ['brows', 19.99], ['chinLip', 16.99], ['nostrils', 12.99], ['ears', 12.99],
                ['fullArms', 49.99], ['upperArms', 19.99], ['forearms', 19.99], ['armpits', 14.99], ['fullStomach', 35.99],
                ['bikiniClassic', 49.99], ['brazilian', 79.99], ['fullLegs', 79.99], ['upperLegs', 39.99], ['lowerLegs', 49.99],
            ],
            'men' => [
                ['armpits', 19.99], ['fullArms', 49.99], ['upperArms', 35.99], ['forearms', 35.99], ['fullLegs', 88.99],
                ['stomachLine', 19.99], ['fullStomach', 39.99], ['chest', 39.99], ['chestStomach', 69.99], ['back', 69.99],
                ['lowerBack', 39.99], ['upperBack', 39.99],
            ],
            'menFacial' => [
                ['fullCombo', 49.99], ['beardContouring', 29.99], ['browContouring', 19.99], ['cheeks', 15.99], ['ears', 15.99], ['nostrils', 15.99],
            ],
        ];

        foreach ($groups as $groupIndex => $groupItems) {
            $group = WaxingGroup::query()->updateOrCreate(
                ['key' => $groupIndex],
                ['title' => $waxingCopy[$groupIndex], 'sort_order' => array_search($groupIndex, array_keys($groups), true) + 1, 'is_active' => true],
            );

            $group->items()->delete();

            foreach ($groupItems as $index => [$key, $amount]) {
                $group->items()->create([
                    'key' => $key,
                    'title' => $items[$key],
                    'amount' => $amount,
                    'currency' => 'USD',
                    'display_price' => ['en' => '$'.number_format($amount, 2), 'ru' => '$'.number_format($amount, 2)],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }

    /**
     * @param  array<string, Service>  $services
     */
    protected function seedSpecialists(array $services): void
    {
        $specialists = [
            [
                'key' => 'esthetician',
                'slug' => ['en' => 'studio-esthetician', 'ru' => 'specialist-estetist'],
                'name' => ['en' => 'Studio Esthetician', 'ru' => 'Специалист-эстетист'],
                'title' => ['en' => 'Skin, brows and body care specialist', 'ru' => 'Специалист по коже, бровям и телу'],
                'bio' => ['en' => 'Initial seed specialist for facials, brows, programs and body treatments.', 'ru' => 'Стартовый специалист для ухода за лицом, бровей, программ и процедур тела.'],
                'services' => array_keys($services),
            ],
            [
                'key' => 'vladimir-chernov',
                'slug' => ['en' => 'vladimir-chernov', 'ru' => 'vladimir-chernov'],
                'name' => ['en' => 'Vladimir Chernov', 'ru' => 'Владимир Чернов'],
                'title' => ['en' => 'Massage specialist', 'ru' => 'Массажист'],
                'bio' => ['en' => 'Massage specialist for rehabilitative bodywork.', 'ru' => 'Специалист по реабилитационному массажу тела.'],
                'services' => ['full-body-massage'],
            ],
            [
                'key' => 'acne-specialist',
                'slug' => ['en' => 'acne-specialist', 'ru' => 'specialist-po-akne'],
                'name' => ['en' => 'Acne Program Specialist', 'ru' => 'Специалист по акне'],
                'title' => ['en' => 'Acne and pigmentation programs', 'ru' => 'Программы акне и пигментации'],
                'bio' => ['en' => 'Initial seed specialist for acne and pigmentation programs.', 'ru' => 'Стартовый специалист для программ акне и пигментации.'],
                'services' => ['pro-acne', 'mild-acne-program', 'standard-acne-program', 'virtual-acne-program', 'pigmentation-program'],
            ],
        ];

        foreach ($specialists as $index => $specialistData) {
            $specialist = Specialist::query()->updateOrCreate(
                ['slug->en' => $specialistData['slug']['en']],
                [
                    'slug' => $specialistData['slug'],
                    'name' => $specialistData['name'],
                    'title' => $specialistData['title'],
                    'bio' => $specialistData['bio'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );

            $sync = [];

            foreach ($specialistData['services'] as $serviceIndex => $serviceKey) {
                if (isset($services[$serviceKey])) {
                    $sync[$services[$serviceKey]->id] = ['sort_order' => $serviceIndex + 1];
                }
            }

            $specialist->services()->sync($sync);
        }
    }

    protected function seedBlog(): void
    {
        $categories = [
            [
                'key' => 'skin-care',
                'slug' => ['en' => 'skin-care', 'ru' => 'uhod-za-kozhey'],
                'title' => ['en' => 'Skin care', 'ru' => 'Уход за кожей'],
                'description' => ['en' => 'Guides for healthy skin between visits.', 'ru' => 'Материалы о здоровой коже между визитами.'],
            ],
            [
                'key' => 'studio-news',
                'slug' => ['en' => 'studio-news', 'ru' => 'novosti-studii'],
                'title' => ['en' => 'Studio news', 'ru' => 'Новости студии'],
                'description' => ['en' => 'Updates from Elegant Beauty Studio.', 'ru' => 'Новости Elegant Beauty Studio.'],
            ],
        ];

        $models = [];

        foreach ($categories as $index => $category) {
            $models[$category['key']] = BlogCategory::query()->updateOrCreate(
                ['slug->en' => $category['slug']['en']],
                $category + ['sort_order' => $index + 1, 'is_active' => true],
            );
        }

        $posts = [
            [
                'category' => 'skin-care',
                'slug' => ['en' => 'how-to-prepare-for-a-facial', 'ru' => 'kak-podgotovitsya-k-uhodu-za-litsom'],
                'title' => ['en' => 'How to prepare for a facial', 'ru' => 'Как подготовиться к уходу за лицом'],
                'excerpt' => ['en' => 'Simple steps that help your appointment go smoothly.', 'ru' => 'Простые шаги, чтобы процедура прошла спокойно и эффективно.'],
                'body' => [
                    'en' => ['Avoid strong exfoliants before your visit, arrive with clean skin if possible, and tell your specialist about active products or medications.'],
                    'ru' => ['Избегайте сильных эксфолиантов перед визитом, по возможности приходите с чистой кожей и расскажите специалисту об активных средствах или препаратах.'],
                ],
                'image' => '/img/home/products.jpg',
            ],
            [
                'category' => 'skin-care',
                'slug' => ['en' => 'why-consistency-matters-in-acne-care', 'ru' => 'pochemu-vazhna-regulyarnost-pri-akne'],
                'title' => ['en' => 'Why consistency matters in acne care', 'ru' => 'Почему важна регулярность при акне'],
                'excerpt' => ['en' => 'Clear skin is a strategy, not a single appointment.', 'ru' => 'Чистая кожа - это стратегия, а не один визит.'],
                'body' => [
                    'en' => ['Acne care works best when clinic treatments, home skincare and follow-up communication support the same plan over time.'],
                    'ru' => ['Уход при акне работает лучше всего, когда процедуры, домашний уход и связь со специалистом поддерживают один план во времени.'],
                ],
                'image' => '/img/services/pro-acneProgram.jpg',
            ],
            [
                'category' => 'studio-news',
                'slug' => ['en' => 'booking-policy-reminder', 'ru' => 'napominanie-o-pravilah-zapisi'],
                'title' => ['en' => 'Booking policy reminder', 'ru' => 'Напоминание о правилах записи'],
                'excerpt' => ['en' => 'A quick note about deposits, late arrivals and rescheduling.', 'ru' => 'Коротко о депозите, опозданиях и переносе записи.'],
                'body' => [
                    'en' => ['A non-refundable deposit secures your appointment and is deducted from the procedure price. Please give at least 48 hours notice to reschedule.'],
                    'ru' => ['Невозвратный депозит закрепляет время и вычитается из стоимости процедуры. Пожалуйста, предупреждайте о переносе минимум за 48 часов.'],
                ],
                'image' => '/img/9.jpg',
            ],
        ];

        foreach ($posts as $index => $post) {
            BlogPost::query()->updateOrCreate(
                ['slug->en' => $post['slug']['en']],
                [
                    'blog_category_id' => $models[$post['category']]->id,
                    'slug' => $post['slug'],
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'image' => $post['image'],
                    'published_at' => now()->subDays(count($posts) - $index),
                    'is_published' => true,
                ],
            );
        }
    }
}
