<?php

test('404 error page is rendered', function () {
    $response = $this->get('/test-error/404');
    $response->assertStatus(404);
    $response->assertSee('Halaman Tidak Ditemukan');
});

test('500 error page is rendered', function () {
    $response = $this->get('/test-error/500');
    $response->assertStatus(500);
    $response->assertSee('Terjadi Kesalahan Server');
});

test('403 error page is rendered', function () {
    $response = $this->get('/test-error/403');
    $response->assertStatus(403);
    $response->assertSee('Akses Ditolak');
});
