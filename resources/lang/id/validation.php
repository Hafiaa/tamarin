<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut ini berisi pesan error default yang digunakan oleh
    | kelas validasi. Beberapa di antaranya memiliki versi aturan yang berbeda,
    | seperti aturan ukuran. Jangan ragu untuk menyesuaikan pesan ini.
    |
    */

    'failed' => 'Kredensial ini tidak cocok dengan catatan kami.',
    'password' => 'Kata sandi yang dimasukkan salah.',
    'throttle' => 'Terlalu banyak upaya login. Silakan coba lagi dalam :seconds detik.',

    // Pesan validasi kustom lainnya
    'accepted' => ':Attribute harus diterima.',
    'accepted_if' => ':Attribute harus diterima ketika :other adalah :value.',
    'active_url' => ':Attribute bukan URL yang valid.',
    'after' => ':Attribute harus berisi tanggal setelah :date.',
    'after_or_equal' => ':Attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha' => ':Attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':Attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num' => ':Attribute hanya boleh berisi huruf dan angka.',
    'array' => ':Attribute harus berupa array.',
    'before' => ':Attribute harus berisi tanggal sebelum :date.',
    'before_or_equal' => ':Attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between' => [
        'numeric' => ':Attribute harus bernilai antara :min sampai :max.',
        'file' => ':Attribute harus berukuran antara :min sampai :max kilobita.',
        'string' => ':Attribute harus berisi antara :min sampai :max karakter.',
        'array' => ':Attribute harus memiliki antara :min sampai :max anggota.',
    ],
    'boolean' => ':Attribute harus bernilai true atau false',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi salah.',
    'date' => ':Attribute bukan tanggal yang valid.',
    'date_equals' => ':Attribute harus berisi tanggal yang sama dengan :date.',
    'date_format' => ':Attribute tidak sesuai dengan format :format.',
    'different' => ':Attribute dan :other harus berbeda.',
    'digits' => ':Attribute harus terdiri dari :digits digit.',
    'digits_between' => ':Attribute harus terdiri dari :min sampai :max digit.',
    'dimensions' => 'Dimensi gambar :attribute tidak valid.',
    'distinct' => ':Attribute memiliki nilai yang duplikat.',
    'email' => ':Attribute harus berupa alamat email yang valid.',
    'ends_with' => ':Attribute harus diakhiri salah satu dari berikut: :values',
    'exists' => ':Attribute yang dipilih tidak valid.',
    'file' => ':Attribute harus berupa file.',
    'filled' => ':Attribute harus diisi.',
    'gt' => [
        'numeric' => ':Attribute harus bernilai lebih besar dari :value.',
        'file' => ':Attribute harus berukuran lebih besar dari :value kilobita.',
        'string' => ':Attribute harus berisi lebih dari :value karakter.',
        'array' => ':Attribute harus memiliki lebih dari :value anggota.',
    ],
    'gte' => [
        'numeric' => ':Attribute harus bernilai lebih besar atau sama dengan :value.',
        'file' => ':Attribute harus berukuran lebih besar atau sama dengan :value kilobita.',
        'string' => ':Attribute harus berisi minimal :value karakter.',
        'array' => ':Attribute harus memiliki minimal :value anggota.',
    ],
    'image' => ':Attribute harus berupa gambar.',
    'in' => ':Attribute yang dipilih tidak valid.',
    'in_array' => ':Attribute tidak ada di dalam :other.',
    'integer' => ':Attribute harus berupa bilangan bulat.',
    'ip' => ':Attribute harus berupa alamat IP yang valid.',
    'ipv4' => ':Attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => ':Attribute harus berupa alamat IPv6 yang valid.',
    'json' => ':Attribute harus berupa JSON string yang valid.',
    'lt' => [
        'numeric' => ':Attribute harus bernilai kurang dari :value.',
        'file' => ':Attribute harus berukuran kurang dari :value kilobita.',
        'string' => ':Attribute harus berisi kurang dari :value karakter.',
        'array' => ':Attribute harus memiliki kurang dari :value anggota.',
    ],
    'lte' => [
        'numeric' => ':Attribute harus bernilai kurang dari atau sama dengan :value.',
        'file' => ':Attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
        'string' => ':Attribute harus berisi maksimal :value karakter.',
        'array' => ':Attribute harus memiliki maksimal :value anggota.',
    ],
    'max' => [
        'numeric' => ':Attribute tidak boleh lebih besar dari :max.',
        'file' => ':Attribute tidak boleh lebih besar dari :max kilobita.',
        'string' => ':Attribute tidak boleh lebih dari :max karakter.',
        'array' => ':Attribute tidak boleh lebih dari :max anggota.',
    ],
    'mimes' => ':Attribute harus berupa file berjenis: :values.',
    'mimetypes' => ':Attribute harus berupa file berjenis: :values.',
    'min' => [
        'numeric' => ':Attribute minimal bernilai :min.',
        'file' => ':Attribute minimal berukuran :min kilobita.',
        'string' => ':Attribute minimal berisi :min karakter.',
        'array' => ':Attribute minimal memiliki :min anggota.',
    ],
    'multiple_of' => ':Attribute harus merupakan kelipatan dari :value.',
    'not_in' => ':Attribute yang dipilih tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',
    'numeric' => ':Attribute harus berupa angka.',
    'password' => [
        'letters' => ':Attribute harus mengandung setidaknya satu huruf.',
        'mixed' => ':Attribute harus mengandung setidaknya satu huruf kapital dan satu huruf kecil.',
        'numbers' => ':Attribute harus mengandung setidaknya satu angka.',
        'symbols' => ':Attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':Attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present' => ':Attribute wajib ada.',
    'prohibited' => 'Kolom :attribute dilarang.',
    'prohibited_if' => 'Kolom :attribute dilarang bila :other adalah :value.',
    'prohibited_unless' => 'Kolom :attribute dilarang kecuali :other memiliki nilai :values.',
    'prohibits' => 'Kolom :attribute melarang isian :other untuk ditampilkan.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => 'Kolom :attribute wajib diisi.',
    'required_array_keys' => 'Kolom :attribute harus berisi entri untuk: :values.',
    'required_if' => 'Kolom :attribute wajib diisi bila :other adalah :value.',
    'required_unless' => 'Kolom :attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with' => 'Kolom :attribute wajib diisi bila :values tersedia.',
    'required_with_all' => 'Kolom :attribute wajib diisi bila :values tersedia.',
    'required_without' => 'Kolom :attribute wajib diisi bila :values tidak tersedia.',
    'required_without_all' => 'Kolom :attribute wajib diisi bila tidak ada :values yang tersedia.',
    'same' => ':Attribute dan :other harus sama.',
    'size' => [
        'numeric' => ':Attribute harus berukuran :size.',
        'file' => ':Attribute harus berukuran :size kilobyte.',
        'string' => ':Attribute harus berukuran :size karakter.',
        'array' => ':Attribute harus mengandung :size anggota.',
    ],
    'starts_with' => ':Attribute harus diawali salah satu dari berikut: :values',
    'string' => ':Attribute harus berupa string.',
    'timezone' => ':Attribute harus berisi zona waktu yang valid.',
    'unique' => ':Attribute sudah digunakan.',
    'uploaded' => ':Attribute gagal diunggah.',
    'url' => 'Format :attribute tidak valid.',
    'uuid' => ':Attribute harus merupakan UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [
        'name' => 'Nama',
        'email' => 'Alamat Email',
        'password' => 'Kata Sandi',
        'password_confirmation' => 'Konfirmasi Kata Sandi',
        'current_password' => 'Kata Sandi Saat Ini',
        'phone' => 'Nomor Telepon',
        'address' => 'Alamat',
        'title' => 'Judul',
        'content' => 'Konten',
        'description' => 'Deskripsi',
        'image' => 'Gambar',
        'price' => 'Harga',
        'quantity' => 'Jumlah',
        'total' => 'Total',
        'status' => 'Status',
        'date' => 'Tanggal',
        'time' => 'Waktu',
        'datetime' => 'Tanggal & Waktu',
    ],
];
