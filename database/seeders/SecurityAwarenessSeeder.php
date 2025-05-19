<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use App\Models\Role;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SecurityAwarenessSeeder extends Seeder
{
    // Roles
    public const ROLE_SOFTWARE_ENGINEER     = 'Software Engineer';
    public const ROLE_FINANCE_PROFESSIONAL  = 'Finance Professional';

    // General Topics
    public const TOPIC_EMAIL_USE               = 'Email Use (Phishing)';
    public const TOPIC_INCIDENT_REPORTING      = 'Incident Reporting';
    public const TOPIC_PASSWORD_MANAGEMENT     = 'Password Management';
    public const TOPIC_MALICIOUS_SOFTWARE      = 'Malicious Software';
    public const TOPIC_SOCIAL_ENGINEERING      = 'Social Engineering';

    // Finance-specific Topics
    public const TOPIC_INVOICE_FRAUD           = 'Invoice Fraud';
    public const TOPIC_SECURE_FILE_SHARING     = 'Secure File Sharing';
    public const TOPIC_MOBILE_DEVICE_SECURITY  = 'Mobile Device Security';

    // Engineer-specific Topics
    public const TOPIC_OWASP_SECURE_CODING     = 'OWASP Top 10 (Secure Coding)';
    public const TOPIC_API_SECURITY            = 'API Security';
    public const TOPIC_DEVELOPMENT_COMPLIANCE  = 'Development Compliance';

    public function run()
    {
        // 1) Roles
        $roles = [];
        $roles[self::ROLE_SOFTWARE_ENGINEER]    = Role::create(['name' => self::ROLE_SOFTWARE_ENGINEER]);
        $roles[self::ROLE_FINANCE_PROFESSIONAL] = Role::create(['name' => self::ROLE_FINANCE_PROFESSIONAL]);

        User::create([
            'name'     => 'Software Engineer',
            'email'    => 'software@example.com',
            'password' => Hash::make('Software'),
            'role_id'  => $roles[self::ROLE_SOFTWARE_ENGINEER]->id,
        ]);

        User::create([
            'name'     => 'Finance Professional',
            'email'    => 'finance@example.com',
            'password' => Hash::make('Finance'),
            'role_id'  => $roles[self::ROLE_FINANCE_PROFESSIONAL]->id,
        ]);

        // 2) Topics
        $allTopicNames = [
            self::TOPIC_EMAIL_USE,
            self::TOPIC_INCIDENT_REPORTING,
            self::TOPIC_PASSWORD_MANAGEMENT,
            self::TOPIC_MALICIOUS_SOFTWARE,
            self::TOPIC_SOCIAL_ENGINEERING,
            self::TOPIC_INVOICE_FRAUD,
            self::TOPIC_SECURE_FILE_SHARING,
            self::TOPIC_MOBILE_DEVICE_SECURITY,
            self::TOPIC_OWASP_SECURE_CODING,
            self::TOPIC_API_SECURITY,
            self::TOPIC_DEVELOPMENT_COMPLIANCE,
        ];

        $topics = [];
        foreach ($allTopicNames as $name) {
            $topics[$name] = Topic::create([
                'name'           => $name,
                'description'    => "Overview of {$name}.",
                'video_url'      => "https://pic.pikbest.com/00/20/37/273888piC6zA.mp4",
                'required_score' => 75,
            ]);
        }

        //
        // 3) Assign topics to roles
        //
        // These five are “general” and go to both roles:
        $general = [
            $topics[self::TOPIC_EMAIL_USE]->id,
            $topics[self::TOPIC_INCIDENT_REPORTING]->id,
            $topics[self::TOPIC_PASSWORD_MANAGEMENT]->id,
            $topics[self::TOPIC_MALICIOUS_SOFTWARE]->id,
            $topics[self::TOPIC_SOCIAL_ENGINEERING]->id,
        ];

        // Software Engineer also gets secure‐coding, API, compliance
        $softwareEngineerTopics = array_merge(
            $general,
            [
                $topics[self::TOPIC_OWASP_SECURE_CODING]->id,
                $topics[self::TOPIC_API_SECURITY]->id,
                $topics[self::TOPIC_DEVELOPMENT_COMPLIANCE]->id,
            ]
        );
        $roles[self::ROLE_SOFTWARE_ENGINEER]
            ->topics()
            ->attach($softwareEngineerTopics);

        // Finance Professional also gets invoice‐fraud, file-sharing, mobile security
        $financeTopics = array_merge(
            $general,
            [
                $topics[self::TOPIC_INVOICE_FRAUD]->id,
                $topics[self::TOPIC_SECURE_FILE_SHARING]->id,
                $topics[self::TOPIC_MOBILE_DEVICE_SECURITY]->id,
            ]
        );
        $roles[self::ROLE_FINANCE_PROFESSIONAL]
            ->topics()
            ->attach($financeTopics);

        // 4) Questions & Options
        $topicQuestions = [

            // --- Email Use (Phishing) ---
            self::TOPIC_EMAIL_USE => [
                [
                    'text'    => 'Which of the following are common signs of a phishing email?',
                    'options' => [
                        ['text' => 'Unfamiliar sender address',                   'is_correct' => true],
                        ['text' => 'Personalized greeting using your full name', 'is_correct' => false],
                        ['text' => 'Urgent request for immediate action',        'is_correct' => true],
                        ['text' => 'Internal company logo in the email',         'is_correct' => false],
                    ],
                ],
                [
                    'text'    => 'What should you do if you receive a suspicious email?',
                    'options' => [
                        ['text' => 'Click the link to see where it leads',                     'is_correct' => false],
                        ['text' => 'Forward it to your IT/security team',                      'is_correct' => true],
                        ['text' => 'Avoid clicking any links or downloading attachments',      'is_correct' => true],
                        ['text' => 'Delete it without reporting it',                          'is_correct' => false],
                    ],
                ],
                [
                    'text'    => 'Why is phishing a persistent threat in email communication?',
                    'options' => [
                        ['text' => 'It is easy to detect with antivirus software',             'is_correct' => false],
                        ['text' => 'Attackers use social engineering to exploit human behavior','is_correct' => true],
                        ['text' => 'It only affects outdated systems',                         'is_correct' => false],
                        ['text' => 'Phishing emails often look legitimate',                    'is_correct' => true],
                    ],
                ],
                [
                    'text'    => 'Which best practices help reduce the risk of phishing attacks?',
                    'options' => [
                        ['text' => 'Ignoring emails from known contacts',                     'is_correct' => false],
                        ['text' => 'Verifying unexpected emails through a secondary channel', 'is_correct' => true],
                        ['text' => 'Reporting suspected phishing attempts to IT',             'is_correct' => true],
                        ['text' => 'Using weak passwords for email access',                   'is_correct' => false],
                    ],
                ],
            ],

            // --- Incident Reporting ---
            self::TOPIC_INCIDENT_REPORTING => [
                [
                    'text'    => 'Why is timely incident reporting important in cybersecurity?',
                    'options' => [
                        ['text' => 'It allows the organization to investigate the employee',  'is_correct' => true],
                        ['text' => 'It supports quick containment and damage control',         'is_correct' => true],
                        ['text' => 'It helps train other staff by identifying blame',          'is_correct' => false],
                        ['text' => 'It ensures compliance with regulations',                   'is_correct' => false],
                    ],
                ],
                [
                    'text'    => 'Which of the following actions count as incident reporting?',
                    'options' => [
                        ['text' => 'Notifying your manager about a suspected breach',         'is_correct' => true],
                        ['text' => 'Logging a ticket in the IT helpdesk system',             'is_correct' => true],
                        ['text' => 'Ignoring a suspected security event',                     'is_correct' => false],
                        ['text' => 'Discussing it with colleagues over lunch',                'is_correct' => false],
                    ],
                ],
                [
                    'text'    => 'What are common barriers to incident reporting?',
                    'options' => [
                        ['text' => 'Employees feel encouraged by leadership',                'is_correct' => false],
                        ['text' => 'Fear of being blamed for the issue',                     'is_correct' => true],
                        ['text' => 'Unclear reporting procedures',                           'is_correct' => true],
                        ['text' => 'Having too many tools available',                        'is_correct' => false],
                    ],
                ],
                [
                    'text'    => 'How can organizations encourage incident reporting?',
                    'options' => [
                        ['text' => 'Punishing those who fail to report',                    'is_correct' => false],
                        ['text' => 'Simplifying the reporting process',                     'is_correct' => true],
                        ['text' => 'Promoting a no-blame culture',                          'is_correct' => true],
                        ['text' => 'Limiting who is allowed to report',                     'is_correct' => false],
                    ],
                ],
            ],

            // --- Password Management ---
            self::TOPIC_PASSWORD_MANAGEMENT => [
                [
                    'text'    => 'What are recommended practices for secure password management?',
                    'options' => [
                        ['text' => 'Reusing passwords across platforms',                     'is_correct' => false],
                        ['text' => 'Using a password manager',                              'is_correct' => true],
                        ['text' => 'Creating complex and unique passwords',                 'is_correct' => true],
                        ['text' => 'Writing passwords on sticky notes',                     'is_correct' => false],
                    ],
                ],
                [
                    'text'    => 'Why is using the same password for multiple accounts risky?',
                    'options' => [
                        ['text' => 'It’s only risky if you use public Wi-Fi',              'is_correct' => false],
                        ['text' => 'A breach of one service can compromise all accounts',  'is_correct' => true],
                        ['text' => 'It increases convenience for the user',                'is_correct' => false],
                        ['text' => 'It facilitates credential stuffing attacks',           'is_correct' => true],
                    ],
                ],
                [
                    'text'    => 'What characterizes a strong password?',
                    'options' => [
                        ['text' => 'Includes a mix of letters, numbers, and special characters','is_correct' => true],
                        ['text' => 'Is at least 8–12 characters long',                     'is_correct' => true],
                        ['text' => 'Contains only your name and birth year',                'is_correct' => false],
                        ['text' => 'Easy to remember and share',                            'is_correct' => false],
                    ],
                ],
                [
                    'text'    => 'How can organizations promote better password practices?',
                    'options' => [
                        ['text' => 'Require frequent password changes',                    'is_correct' => true],
                        ['text' => 'Enforce minimum complexity standards',                 'is_correct' => true],
                        ['text' => 'Let employees choose any password they like',          'is_correct' => false],
                        ['text' => 'Discourage the use of password managers',             'is_correct' => false],
                    ],
                ],
            ],

            // --- Malicious Software ---
            self::TOPIC_MALICIOUS_SOFTWARE => [
                [
                    'text'    => 'What is the primary risk of malicious software (malware)?',
                    'options' => [
                        ['text' => 'It only affects mobile devices',                      'is_correct' => false],
                        ['text' => 'It can steal, encrypt, or delete sensitive data',    'is_correct' => true],
                        ['text' => 'It slows down email communications',                 'is_correct' => false],
                        ['text' => 'It can be used to control or spy on systems',        'is_correct' => true],
                    ],
                ],
                [
                    'text'    => 'How can users protect against malware?',
                    'options' => [
                        ['text' => 'Keep software and operating systems up to date',      'is_correct' => true],
                        ['text' => 'Open attachments from unknown senders',              'is_correct' => false],
                        ['text' => 'Avoid clicking on suspicious ads',                   'is_correct' => true],
                        ['text' => 'Disable antivirus software for better speed',        'is_correct' => false],
                    ],
                ],
                [
                    'text'    => 'What are common types of malware?',
                    'options' => [
                        ['text' => 'Firewalls and VPNs',                                 'is_correct' => false],
                        ['text' => 'Viruses and ransomware',                            'is_correct' => true],
                        ['text' => 'Worms and spyware',                                 'is_correct' => true],
                        ['text' => 'Anti-malware software',                             'is_correct' => false],
                    ],
                ],
                [
                    'text'    => 'What actions can trigger malware infections?',
                    'options' => [
                        ['text' => 'Installing software from unverified sources',       'is_correct' => true],
                        ['text' => 'Plugging in unknown USB drives',                    'is_correct' => true],
                        ['text' => 'Reading official company emails',                   'is_correct' => false],
                        ['text' => 'Visiting legitimate websites only',                 'is_correct' => false],
                    ],
                ],
            ],

            // --- Social Engineering ---
            self::TOPIC_SOCIAL_ENGINEERING => [
                [
                    'text'    => 'What is the main goal of social engineering?',
                    'options' => [
                        ['text' => 'To exploit human behavior for unauthorized access','is_correct' => true],
                        ['text' => 'To enhance social media profiles',                'is_correct' => false],
                        ['text' => 'To use advanced hacking tools',                   'is_correct' => false],
                        ['text' => 'To trick victims through impersonation or manipulation','is_correct'=> true],
                    ],
                ],
                [
                    'text'    => 'Which scenarios are typical of social engineering attacks?',
                    'options' => [
                        ['text' => 'An unknown person calls pretending to be IT support','is_correct'=> true],
                        ['text' => 'A hacker brute-forces a password',                'is_correct'=> false],
                        ['text' => 'Someone tailgates into a secure building',        'is_correct'=> true],
                        ['text' => 'A user forgets their login credentials',         'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'How can employees defend against social engineering?',
                    'options' => [
                        ['text' => 'Always verify unexpected requests',              'is_correct'=> true],
                        ['text' => 'Share passwords only with managers',             'is_correct'=> false],
                        ['text' => 'Avoid providing personal or system info over phone','is_correct'=> true],
                        ['text' => 'Use the same security procedures for everyone',   'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'Which of the following may indicate a social engineering attempt?',
                    'options' => [
                        ['text' => 'A request marked as "urgent" or "confidential" from a new contact','is_correct'=> true],
                        ['text' => 'Routine IT system updates',                            'is_correct'=> false],
                        ['text' => 'A stranger asking for badge access',                    'is_correct'=> true],
                        ['text' => 'A scheduled meeting with your supervisor',              'is_correct'=> false],
                    ],
                ],
            ],

            // --- Invoice Fraud ---
            self::TOPIC_INVOICE_FRAUD => [
                [
                    'text'    => 'You receive an email invoice from a familiar vendor’s email address claiming their bank account has changed. What should you do?',
                    'options' => [
                        ['text' => 'Verify the request through a trusted channel',                       'is_correct'=> true],
                        ['text' => 'Pay immediately as instructed',                                      'is_correct'=> false],
                        ['text' => 'Consult a manager or follow internal approval processes',            'is_correct'=> true],
                        ['text' => 'Use the new bank details from the email directly',                  'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'You get an urgent payment request from the CEO via email while they’re traveling. What are red flags?',
                    'options' => [
                        ['text' => 'Urgency and pressure in the request',                                'is_correct'=> true],
                        ['text' => 'Changes in payment details or accounts',                             'is_correct'=> false],
                        ['text' => 'Following standard company procedure',                               'is_correct'=> false],
                        ['text' => 'Unverified sender identity or unusual email domain',                 'is_correct'=> true],
                    ],
                ],
                [
                    'text'    => 'Which internal controls help prevent invoice fraud in finance departments?',
                    'options' => [
                        ['text' => 'Requiring multiple approvals for large or new payments',             'is_correct'=> true],
                        ['text' => 'Allowing one person to both approve and pay invoices',               'is_correct'=> false],
                        ['text' => 'Verifying new vendors or account changes thoroughly',               'is_correct'=> true],
                        ['text' => 'Letting staff use personal judgment without formal checks',         'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'After paying a fraudulent invoice, what should you do according to best practices?',
                    'options' => [
                        ['text' => 'Immediately investigate and halt further payments',                 'is_correct'=> true],
                        ['text' => 'Secure evidence and involve legal authorities',                    'is_correct'=> true],
                        ['text' => 'Wait for the vendor to report the issue',                          'is_correct'=> false],
                        ['text' => 'Keep it quiet and avoid notifying anyone',                         'is_correct'=> false],
                    ],
                ],
            ],

            // --- Secure File Sharing ---
            self::TOPIC_SECURE_FILE_SHARING => [
                [
                    'text'    => 'You need to send a spreadsheet with sensitive data to an external auditor. What are secure file-sharing practices?',
                    'options' => [
                        ['text' => 'Use an approved secure file-sharing service or encrypted email','is_correct'=> true],
                        ['text' => 'Upload it to any convenient cloud drive (e.g., personal Google Drive)','is_correct'=> false],
                        ['text' => 'Protect the file with strong encryption if it must be emailed','is_correct'=> true],
                        ['text' => 'Send it over public email without encryption since the auditor is trusted','is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'A coworker wants to email a ZIP file of customer data. Why is this risky, and what’s better?',
                    'options' => [
                        ['text' => 'Weak ZIP encryption can be easily cracked',                       'is_correct'=> true],
                        ['text' => 'ZIP encryption is unbreakable if the password is long',           'is_correct'=> false],
                        ['text' => 'Use stronger encryption or secure transfer methods',             'is_correct'=> true],
                        ['text' => 'File size makes it secure enough for transfer',                   'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'You’re sharing PII with a client. What are compliant methods?',
                    'options' => [
                        ['text' => 'Send files via the company’s secure file exchange portal',       'is_correct'=> true],
                        ['text' => 'Use your personal cloud drive and email the link',                'is_correct'=> false],
                        ['text' => 'Apply encryption and share the decryption key separately',        'is_correct'=> true],
                        ['text' => 'Turn off encryption to make it easier for the client',           'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'What are risks of using public Wi-Fi to send company files, and how can they be mitigated?',
                    'options' => [
                        ['text' => 'Eavesdropping and man-in-the-middle attacks',                    'is_correct'=> true],
                        ['text' => 'Public Wi-Fi is safe if it has a password',                      'is_correct'=> false],
                        ['text' => 'Use of a VPN or secure hotspot as a safeguard',                 'is_correct'=> true],
                        ['text' => 'No risk – modern websites all use HTTPS',                       'is_correct'=> false],
                    ],
                ],
            ],

            // --- Mobile Device Security ---
            self::TOPIC_MOBILE_DEVICE_SECURITY => [
                [
                    'text'    => 'How should you secure a company-issued smartphone with work data?',
                    'options' => [
                        ['text' => 'Use a strong PIN/password and auto-lock',                    'is_correct'=> true],
                        ['text' => 'Jailbreak or root the phone for full control',                'is_correct'=> false],
                        ['text' => 'Keep the OS and apps updated',                               'is_correct'=> true],
                        ['text' => 'Use only Wi-Fi and turn off mobile data',                    'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'How can you securely access company files using your work phone on café Wi-Fi?',
                    'options' => [
                        ['text' => 'Use a VPN when on untrusted networks',                       'is_correct'=> true],
                        ['text' => 'Consider using cellular data or a personal hotspot',          'is_correct'=> true],
                        ['text' => 'Trust “HTTPS” to protect everything',                        'is_correct'=> false],
                        ['text' => 'Assume public Wi-Fi is safe if it’s in a well-known café',    'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'What should be done after losing an unlocked work phone with company data?',
                    'options' => [
                        ['text' => 'Report the loss immediately to IT/security',               'is_correct'=> true],
                        ['text' => 'Allow IT to remotely wipe the device',                     'is_correct'=> true],
                        ['text' => 'Wait a few days to see if it’s found',                     'is_correct'=> false],
                        ['text' => 'Track the phone silently and not tell anyone',             'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'What are the risks of ignoring OS updates on work devices?',
                    'options' => [
                        ['text' => 'The device remains vulnerable to known exploits',           'is_correct'=> true],
                        ['text' => 'Complying with policy: updates are required',              'is_correct'=> false],
                        ['text' => 'It’s fine if the device is working OK',                    'is_correct'=> false],
                        ['text' => 'Only major version upgrades matter, not small patches',    'is_correct'=> false],
                    ],
                ],
            ],

            // --- OWASP Top 10 (Secure Coding) ---
            self::TOPIC_OWASP_SECURE_CODING => [
                [
                    'text'    => 'How do you prevent SQL injection when using user input in database queries?',
                    'options' => [
                        ['text' => 'Use parameterized queries or a safe API',                  'is_correct'=> true],
                        ['text' => 'Trust the client-side validation to sanitize inputs',     'is_correct'=> false],
                        ['text' => 'Escape special characters in any remaining dynamic queries','is_correct'=> true],
                        ['text' => 'Build SQL queries by directly concatenating user inputs',  'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'How do you prevent Cross-Site Scripting (XSS) when showing user input in web pages?',
                    'options' => [
                        ['text' => 'Encode (escape) user input before outputting to HTML',     'is_correct'=> true],
                        ['text' => 'Use frameworks or templating libraries that auto-encode',   'is_correct'=> true],
                        ['text' => 'Disable HTML escaping to preserve user content exactly',   'is_correct'=> false],
                        ['text' => 'Trust browser extensions to block any script injection',   'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'What prevents broken access control issues in APIs or web apps?',
                    'options' => [
                        ['text' => 'Enforce server-side authorization on every request',       'is_correct'=> true],
                        ['text' => 'Adopt a “deny by default” strategy',                       'is_correct'=> true],
                        ['text' => 'Hide admin URLs on the UI front-end',                      'is_correct'=> false],
                        ['text' => 'Let users manage their own access permissions',            'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'Why is using outdated libraries with known vulnerabilities a bad practice?',
                    'options' => [
                        ['text' => 'Known vulnerabilities are low-hanging fruit for attackers', 'is_correct'=> true],
                        ['text' => 'Older libraries are always more secure than newer ones',  'is_correct'=> false],
                        ['text' => 'It’s acceptable if the system is not accessible to the public','is_correct'=> false],
                        ['text' => 'It may violate compliance requirements',                   'is_correct'=> true],
                    ],
                ],
            ],

            // --- API Security ---
            self::TOPIC_API_SECURITY => [
                [
                    'text'    => 'An API returns full user data, including fields the client doesn’t use. What’s wrong and how to fix it?',
                    'options' => [
                        ['text' => 'Excessive Data Exposure',                                 'is_correct'=> true],
                        ['text' => 'Relying on the client to hide data is fine',             'is_correct'=> false],
                        ['text' => 'Limit data returned to only what’s needed',              'is_correct'=> true],
                        ['text' => 'Logging everything makes it easier to audit',            'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'A login API allows unlimited guesses. What are the security risks and defenses?',
                    'options' => [
                        ['text' => 'Brute-force and DoS risk',                                'is_correct'=> true],
                        ['text' => 'Implement rate limiting or CAPTCHA',                     'is_correct'=> true],
                        ['text' => 'Allow unlimited login attempts for usability',           'is_correct'=> false],
                        ['text' => 'Only protect admin endpoints',                           'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'An API accepts arbitrary JSON and allows setting fields like isAdmin. What’s wrong and how to fix it?',
                    'options' => [
                        ['text' => 'Mass Assignment (Property Tampering)',                   'is_correct'=> true],
                        ['text' => 'Accepting all fields gives flexibility',                'is_correct'=> false],
                        ['text' => 'Use whitelisting for bindable fields',                  'is_correct'=> true],
                        ['text' => 'Let clients define user roles directly',                'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'An API uses one shared key for all mobile users that never expires. What are the risks?',
                    'options' => [
                        ['text' => 'That’s normal for mobile apps',                         'is_correct'=> false],
                        ['text' => 'Broken User Authentication',                            'is_correct'=> true],
                        ['text' => 'Use proper per-user tokens/sessions',                   'is_correct'=> true],
                        ['text' => 'Keys should be hardcoded in the app',                  'is_correct'=> false],
                    ],
                ],
            ],

            // --- Development Compliance ---
            self::TOPIC_DEVELOPMENT_COMPLIANCE => [
                [
                    'text'    => 'What must you do when storing user passwords securely?',
                    'options' => [
                        ['text' => 'Store passwords as salted hashes, not plain text',     'is_correct'=> true],
                        ['text' => 'Use hash algorithms resistant to brute-force',          'is_correct'=> true],
                        ['text' => 'Encrypt passwords so admins can retrieve them',        'is_correct'=> false],
                        ['text' => 'Keep plain text passwords in a secure server',         'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'How should developers handle personal data to meet GDPR obligations?',
                    'options' => [
                        ['text' => 'Encrypt personal data at rest and in transit',         'is_correct'=> true],
                        ['text' => 'Store all personal data in logs for auditing',         'is_correct'=> false],
                        ['text' => 'Implement “privacy by design” principles',             'is_correct'=> true],
                        ['text' => 'Use real personal data for development testing',       'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'What secure development practices meet OWASP/NIST standards?',
                    'options' => [
                        ['text' => 'Perform regular code reviews with a security focus',   'is_correct'=> true],
                        ['text' => 'Skip security testing if on a tight deadline',         'is_correct'=> false],
                        ['text' => 'Use static analysis (SAST) tools',                    'is_correct'=> true],
                        ['text' => 'Avoid documentation for faster delivery',             'is_correct'=> false],
                    ],
                ],
                [
                    'text'    => 'What are the risks of logging passwords or card numbers during debugging?',
                    'options' => [
                        ['text' => 'Logging sensitive data violates data protection standards','is_correct'=> true],
                        ['text' => 'Logs are private so it’s not a concern',               'is_correct'=> false],
                        ['text' => 'Remove or mask sensitive information in logs',         'is_correct'=> true],
                        ['text' => 'Only production logs matter for compliance',           'is_correct'=> false],
                    ],
                ],
            ],

        ];

        foreach ($topicQuestions as $topicName => $questions) {
            $topic = $topics[$topicName];
            foreach ($questions as $qData) {
                $q = Question::create([
                    'topic_id' => $topic->id,
                    'text'     => $qData['text'],
                ]);
                foreach ($qData['options'] as $opt) {
                    Option::create([
                        'question_id' => $q->id,
                        'text'        => $opt['text'],
                        'is_correct'  => $opt['is_correct'],
                    ]);
                }
            }
        }
    }
}
