cd c:\xampp\htdocs\hydrosite\public_html
php artisan tinker --execute="
echo '=== VERIFICANDO DATOS EN configuracion_cuentas_iniciales ===';
$registros = DB::table('configuracion_cuentas_iniciales')
    ->select('id', 'banco', 'numero_cuenta', 'saldo_inicial', 'org_id')
    ->get();
foreach($registros as $registro) {
    echo 'ID: ' . $registro->id . ' | Banco: ' . ($registro->banco ?? 'NULL') . ' | Cuenta: ' . ($registro->numero_cuenta ?? 'NULL') . ' | Saldo: ' . $registro->saldo_inicial . PHP_EOL;
}
echo '=== FIN ===';
"
