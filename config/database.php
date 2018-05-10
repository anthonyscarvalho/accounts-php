<?php
$_latest_database_version = "2";
$_tableConfig =
    [
    [
        "name" => "adwords",
        "columns" =>
        [
            'id', 'clients', 'date', 'credit', 'debit', 'comment', 'commission'
        ]

    ],
    [
        "name" => "ad_campaigns",
        "columns" =>
        [
            'id', 'name', 'created'
        ]

    ],
    [
        "name" => "ad_clients",
        "columns" =>
        [
            'id', 'campaings', 'clients', 'added'
        ]

    ],
    [
        "name" => "ad_emails",
        "columns" =>
        [
            'id', 'campaigns', 'users', 'contacts', 'subject', 'body', 'date', 'attachment', 'status'
        ]

    ],
    [
        "name" => "ad_logs",
        "columns" =>
        [
            'id', 'campaigns', 'users', 'affected_table', 'action', 'date', 'data'
        ]

    ],
    [
        "name" => "ad_transactions",
        "columns" =>
        [
            'id', 'campaigns', 'clients', 'date', 'credit', 'debit', 'comment', 'commission'
        ]
    ],
    [
        "name" => "attachments",
        "columns" =>
        [
            'id', 'clients', 'description', 'date', 'attachment', 'accepted', 'accepted_date'
        ]

    ],
    [
        "name" => "categories",
        "columns" =>
        [
            'id', 'category', 'price', 'link', 'canceled', 'canceled_date'
        ]

    ],
    [
        "name" => "clients",
        "columns" =>
        [
            'id', 'business', 'vat', 'number', 'fax', 'registration', 'billing_address', 'city', 'postal_code', 'notes', 'canceled', 'signup_date', 'canceled_date', 'bad_client'
        ]

    ],
    [
        "name" => "companies",
        "columns" =>
        [
            'id', 'company', 'invoice_header', 'account_details', 'canceled'
        ]

    ],
    [
        "name" => "contacts",
        "columns" =>
        [
            'id', 'clients', 'name', 'surname', 'contact_number_1', 'contact_number_2', 'email', 'payment', 'invoice', 'receipt', 'suspension', 'adwords', 'quotes', 'creation_date', 'cancled_date', 'canceled'
        ]

    ],
    [
        "name" => "emails",
        "columns" =>
        [
            'id', 'name', 'subject', 'body'
        ]

    ],
    [
        "name" => "email_log",
        "columns" =>
        [
            'id', 'users', 'contacts', 'invoices', 'quotes', 'subject', 'body', 'date', 'status'
        ]

    ],
    [
        "name" => "expenditure",
        "columns" =>
        [
            'id', 'categories', 'companies', 'date', 'amount', 'description', 'type'
        ]

    ],
    [
        "name" => "invoices",
        "columns" =>
        [
            'id', 'clients', 'companies', 'creation_date', 'canceled_date', 'due_date', 'paid_date', 'invoice_total', 'deposit', 'vat', 'notes', 'paid', 'canceled'
        ]

    ],
    [
        "name" => "invoices_emails",
        "columns" =>
        [
            'id', 'invoice', 'email_type', 'date'
        ]

    ],
    [
        "name" => "invoices_items",
        "columns" =>
        [
            'id', 'invoices', 'products', 'categories', 'date', 'description', 'price'
        ]

    ],
    [
        "name" => "items",
        "columns" =>
        [
            'id', 'item', 'price', 'canceled', 'canceled_date'
        ]

    ],
    [
        "name" => "jobs",
        "columns" =>
        [
            'id', 'clients', 'users', 'categories', 'quoted', 'received', 'end', 'design', 'seo', 'google', 'yahoo', 'bing', 'dmz', 'traveldex', 'links', 'portfolio', 'facebook', 'invoice', 'paid', 'notes', 'complete', 'creation_date', 'canceled', 'canceled_date'
        ]

    ],
    [
        "name" => "logs",
        "columns" =>
        [
            'id', 'clients', 'users', 'date', 'action', 'affected_table', 'date'
        ]

    ],
    [
        "name" => "products",
        "columns" =>
        [
            'id', 'clients', 'companies', 'categories', 'date', 'year', 'month', 'description', 'price', 'renewable', 'period', 'canceled', 'canceled_date'
        ]

    ],
    [
        "name" => "quotations",
        "columns" =>
        [
            'id', 'clients', 'deposit', 'scope', 'content', 'signature', 'annexure', 'products', 'creation_date', 'canceled_date', 'accepted_date', 'notes', 'accepted', 'canceled', 'link'
        ]

    ],
    [
        "name" => "quotations_accepted",
        "columns" =>
        [
            'id', 'quotations', 'contacts', 'date', 'status'
        ]

    ],
    [
        "name" => "quotations_emails",
        "columns" =>
        [
            'id', 'quote', 'date'
        ]

    ],
    [
        "name" => "sessions",
        "columns" =>
        [
            'id', 'session', 'logged_in', 'time', 'user', 'last_url'
        ]

    ],
    [
        "name" => "settings",
        "columns" =>
        [
            'id', 'name', 'value'
        ]

    ],
    [
        "name" => "template_attachments",
        "columns" =>
        [
            'id', 'name', 'template', 'canceled'
        ]

    ],
    [
        "name" => "template_emails",
        "columns" =>
        [
            'id', 'name', 'subject', 'body', 'canceled'
        ]

    ],
    [
        "name" => "template_quotations",
        "columns" =>
        [
            'id', 'name', 'scope', 'content', 'signature', 'annexure', 'date_created', 'canceled', 'date_canceled'
        ]

    ],
    [
        "name" => "tickets",
        "columns" =>
        [
            'id', 'clients', 'contacts', 'email_from', 'email_to', 'subject', 'content'
        ]

    ],
    [
        "name" => "transactions",
        "columns" =>
        [
            'id', 'clients', 'companies', 'invoices', 'date', 'description', 'credit', 'debit'
        ]

    ],
    [
        "name" => "users",
        "columns" =>
        [
            'id', 'username', 'password', 'name', 'surname', 'email_address', 'last_login', 'roles', 'access', 'canceled'
        ]

    ],
    [
        "name" => "user_roles",
        "columns" =>
        [
            'id', 'role', 'save', 'edit', 'status', 'delete', 'create'
        ]

    ]
];
