<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute harus bernilai true.',
    'active_url'           => ':attribute harus berupa URL yang valid.',
    'after'                => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus berupa tanggal setelah atau sama dengan tanggal :date.',
    'alpha'                => ':attribute harus berupa alphabet.',
    'alpha_dash'           => ':attribute dapat berupa alphabet, nomer, dan garis/ underscore.',
    'alpha_num'            => ':attribute dapat berupa alphabet dan nomer.',
    'array'                => ':attribute harus berupa array.',
    'before'               => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus berupa tanggal sebelum atau sama dengan tanggal :date.',
    'between'              => [
        'numeric' => ':attribute harus diantara :min dan :max.',
        'file'    => ':attribute harus diantara :min and :max kilobyte.',
        'string'  => ':attribute harus diantara :min and :max karakter.',
        'array'   => ':attribute harus diantara :min and :max items.',
    ],
    'boolean'              => ':attribute harus berupa true atau false.',
    'confirmed'            => 'Konfirmasi :attribute tidak sesuai.',
    'current_password'     => 'Kata sandi yang Anda masukkan salah.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_format'          => ':attribute tidak sesuai dengan format :format.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus :digits digit.',
    'digits_between'       => ':attribute harus diantara :min dan :max digit.',
    'dimensions'           => ':attribute mempunyai dimensi gambar yang tidak valid.',
    'distinct'             => ':attribute tidak boleh mempunyai nilai sama.',
    'email'                => ':attribute harus berupa email yang valid.',
    'empty_refferal_code'  => 'selected :attribute tidak valid.',
    'exists'               => 'selected :attribute tidak valid.',
    'exists_encrypt'       => 'selected :attribute tidak valid.',
    'file'                 => ':attribute harus berupa file.',
    'filled'               => ':attribute tidak boleh kosong.',
    'gt'                   => [
        'numeric' => ':attribute harus lebih besar dari :value.',
        'file'    => ':attribute harus lebih besar dari :value kilobyte.',
        'string'  => ':attribute harus lebih besar dari :value karakter.',
        'array'   => ':attribute harus lebih besar dari :value item.',
    ],
    'gte'                  => [
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'file'    => ':attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'string'  => ':attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array'   => ':attribute harus mempunyai :value item atau lebih.',
    ],
    'id_number'            => 'Nomor KTP Anda tidak valid, mohon cek kembali.',
    'image'                => ':attribute harus berupa gambar.',
    'image_upload'         => ':attribute harus berupa tipe file: :values.',
    'in'                   => 'terpilih :attribute tidak valid.',
    'in_array'             => ':attribute tidak ada dalam :other.',
    'integer'              => ':attribute harus berupa integer.',
    'ip'                   => ':attribute harus berupa alamat valid IP.',
    'ipv4'                 => ':attribute harus berupa alamat valid IPv4.',
    'ipv6'                 => ':attribute harus berupa alamat valid IPv6.',
    'json'                 => ':attribute harus berupa string JSON.',
    'lt'                   => [
        'numeric' => ':attribute harus kurang dari :value.',
        'file'    => ':attribute harus kurang dari :value kilobyte.',
        'string'  => ':attribute harus kurang dari :value karakter.',
        'array'   => ':attribute harus kurang dari :value item.',
    ],
    'lte'                  => [
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'file'    => ':attribute harus kurang dari atau sama dengan :value kilobyte.',
        'string'  => ':attribute harus kurang dari atau sama dengan :value karakter.',
        'array'   => ':attribute harus tidak lebih dari :value item.',
    ],
    'max'                  => [
        'numeric' => ':attribute tidak boleh lebih besar dari :max.',
        'file'    => ':attribute tidak boleh lebih besar dari :max kilobyte.',
        'string'  => ':attribute tidak boleh lebih besar dari :max karakter.',
        'array'   => ':attribute tidak boleh lebih dari :max item.',
    ],
    'mimes'                => ':attribute harus berupa file tipe : :values.',
    'mimetypes'            => ':attribute harus berupa file tipe : :values.',
    'min'                  => [
        'numeric' => ':attribute sekurang-kurangnya :min.',
        'file'    => ':attribute sekurang-kurangnya :min kilobyte.',
        'string'  => ':attribute sekurang-kurangnya :min karakter.',
        'array'   => ':attribute sekurang-kurangnya :min item.',
    ],
    'not_in'               => 'yang dipilih :attribute tidak valid.',
    'not_regex'            => ':attribute mempunyai format yang tidak valid.',
    'numeric'              => ':attribute harus berupa nomer.',
    'password'             => 'Pilih kata sandi yang lebih kuat, minimal ada 1 huruf besar dan 1 angka.',
    'password_otc'         => ':attribute tidak valid',
    'phone'                => ':attribute format nya tidak valid.',
    'present'              => ':attribute harus tersedia.',
    'promo_code'           => ':attribute tidak valid.',
    'regex'                => ':attribute mempunyai format tidak valid.',
    'required'             => 'Bidang isian :attribute wajib diisi.',
    'required_if'          => ':attribute harus diisi ketika :other bernilai :value.',
    'required_unless'      => ':attribute harus diisi kecuali :other bernilai :values.',
    'required_with'        => ':attribute harus diisi ketika :values tersedia.',
    'required_with_all'    => ':attribute harus diisi ketika :values tersedia.',
    'required_without'     => ':attribute harus diisi ketika :values tidak tersedia.',
    'required_without_all' => ':attribute harus diisi ketika tidak satupun :values tersedia.',
    'retailer_point_redemption' => 'Maaf, poin anda tidak mencukupi.',
    'retailer_point_expiration' => 'Maaf, penukaran poin sudah kadaluarsa',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => ':attribute harus :size.',
        'file'    => ':attribute harus :size kilobyte.',
        'string'  => ':attribute harus :size karakter.',
        'array'   => ':attribute harus mengandung :size item.',
    ],
    'string'               => ':attribute harus berupa string.',
    'timezone'             => ':attribute harus berupa zona yang valid.',
    'unique'               => ':attribute sudah digunakan.',
    'unique_encrypt'       => ':attribute sudah digunakan.',
    'uploaded'             => ':attribute gagal diupload.',
    'url'                  => ':attribute mempunyai format tidak valid.',
    'verify_password'      => 'Kata sandi yang Anda masukkan salah.',
    'verify_ws_code'       => 'Wholesaler Code tidak valid.',
    'verify_isms_code'     => 'ISMS Code tidak valid.',
    'wholesaler_sku_id'    => ':attribute tidak valid.',
    

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

    'attributes' => [],

];
