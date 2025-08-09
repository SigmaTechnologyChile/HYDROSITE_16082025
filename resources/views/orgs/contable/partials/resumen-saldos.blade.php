<div class="contable-saldos-resumen" id="resumenContainer">
    <div class="contable-saldo-card">
        <div class="contable-saldo-header">
            <div class="contable-saldo-icon">
                <i class="bi bi-wallet-fill"></i>
            </div>
            <div class="contable-saldo-info">
                <h4 class="contable-saldo-title">
                    Caja General
                    @if(isset($cuentaCajaGeneral) && ($cuentaCajaGeneral->banco || $cuentaCajaGeneral->numero_cuenta || $cuentaCajaGeneral->tipo_cuenta || $cuentaCajaGeneral->observaciones))
                        <span class="contable-saldo-tooltip" title="Banco: {{ $cuentaCajaGeneral->banco ? ($cuentaCajaGeneral->banco->nombre ?? '-') : '-' }} | N°: {{ $cuentaCajaGeneral->numero_cuenta ?? '-' }} | Tipo: {{ $cuentaCajaGeneral->tipo_cuenta ?? '-' }} | Obs: {{ $cuentaCajaGeneral->observaciones ?? '-' }}">
                            <i class="bi bi-info-circle"></i>
                        </span>
                    @endif
                </h4>
                <div class="contable-saldo-valor" id="saldoCajaGeneral">
                    ${{ number_format($saldosIniciales['Caja General'] ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="contable-saldo-card">
        <div class="contable-saldo-header">
            <div class="contable-saldo-icon">
                <i class="bi bi-bank"></i>
            </div>
            <div class="contable-saldo-info">
                <h4 class="contable-saldo-title">
                    Cuenta Corriente 1
                    @if(isset($cuentaCorriente1) && ($cuentaCorriente1->banco || $cuentaCorriente1->numero_cuenta || $cuentaCorriente1->tipo_cuenta || $cuentaCorriente1->observaciones))
                        <span class="contable-saldo-tooltip" title="Banco: {{ $cuentaCorriente1->banco ? ($cuentaCorriente1->banco->nombre ?? '-') : '-' }} | N°: {{ $cuentaCorriente1->numero_cuenta ?? '-' }} | Tipo: {{ $cuentaCorriente1->tipo_cuenta ?? '-' }} | Obs: {{ $cuentaCorriente1->observaciones ?? '-' }}">
                            <i class="bi bi-info-circle"></i>
                        </span>
                    @endif
                </h4>
                <div class="contable-saldo-valor" id="saldoCuentaCorriente1">
                    ${{ number_format($saldosIniciales['Cuenta Corriente 1'] ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="contable-saldo-card">
        <div class="contable-saldo-header">
            <div class="contable-saldo-icon">
                <i class="bi bi-bank"></i>
            </div>
            <div class="contable-saldo-info">
                <h4 class="contable-saldo-title">
                    Cuenta Corriente 2
                    @if(isset($cuentaCorriente2) && ($cuentaCorriente2->banco || $cuentaCorriente2->numero_cuenta || $cuentaCorriente2->tipo_cuenta || $cuentaCorriente2->observaciones))
                        <span class="contable-saldo-tooltip" title="Banco: {{ $cuentaCorriente2->banco ? ($cuentaCorriente2->banco->nombre ?? '-') : '-' }} | N°: {{ $cuentaCorriente2->numero_cuenta ?? '-' }} | Tipo: {{ $cuentaCorriente2->tipo_cuenta ?? '-' }} | Obs: {{ $cuentaCorriente2->observaciones ?? '-' }}">
                            <i class="bi bi-info-circle"></i>
                        </span>
                    @endif
                </h4>
                <div class="contable-saldo-valor" id="saldoCuentaCorriente2">
                    ${{ number_format($saldosIniciales['Cuenta Corriente 2'] ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="contable-saldo-card">
        <div class="contable-saldo-header">
            <div class="contable-saldo-icon">
                <i class="bi bi-piggy-bank-fill"></i>
            </div>
            <div class="contable-saldo-info">
                <h4 class="contable-saldo-title">
                    Cuenta de Ahorro
                    @if(isset($cuentaAhorro) && ($cuentaAhorro->banco || $cuentaAhorro->numero_cuenta || $cuentaAhorro->tipo_cuenta || $cuentaAhorro->observaciones))
                        <span class="contable-saldo-tooltip" title="Banco: {{ $cuentaAhorro->banco ? ($cuentaAhorro->banco->nombre ?? '-') : '-' }} | N°: {{ $cuentaAhorro->numero_cuenta ?? '-' }} | Tipo: {{ $cuentaAhorro->tipo_cuenta ?? '-' }} | Obs: {{ $cuentaAhorro->observaciones ?? '-' }}">
                            <i class="bi bi-info-circle"></i>
                        </span>
                    @endif
                </h4>
                <div class="contable-saldo-valor" id="saldoCuentaAhorro">
                    ${{ number_format($saldosIniciales['Cuenta de Ahorro'] ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="contable-saldo-card contable-saldo-total">
        <div class="contable-saldo-header">
            <div class="contable-saldo-icon">
                <i class="bi bi-calculator-fill"></i>
            </div>
            <div class="contable-saldo-info">
                <h4 class="contable-saldo-title">Total General</h4>
                <div class="contable-saldo-valor" id="saldoTotal">
                    ${{ number_format(($saldosIniciales['Caja General'] ?? 0) + ($saldosIniciales['Cuenta Corriente 1'] ?? 0) + ($saldosIniciales['Cuenta Corriente 2'] ?? 0) + ($saldosIniciales['Cuenta de Ahorro'] ?? 0), 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos específicos para el resumen de saldos modernizado */
    .contable-saldos-resumen {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }

    .contable-saldo-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--border-radius-md);
        padding: var(--spacing-lg);
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    .contable-saldo-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }

    .contable-saldo-card.contable-saldo-total {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-color: var(--primary-dark);
    }

    .contable-saldo-card.contable-saldo-total .contable-saldo-icon {
        color: rgba(255, 255, 255, 0.9);
    }

    .contable-saldo-header {
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
    }

    .contable-saldo-icon {
        font-size: 2rem;
        color: var(--primary-color);
        flex-shrink: 0;
    }

    .contable-saldo-info {
        flex: 1;
    }

    .contable-saldo-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--gray-700);
        margin: 0 0 var(--spacing-xs) 0;
        display: flex;
        align-items: center;
        gap: var(--spacing-xs);
    }

    .contable-saldo-card.contable-saldo-total .contable-saldo-title {
        color: rgba(255, 255, 255, 0.95);
    }

    .contable-saldo-valor {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
    }

    .contable-saldo-card.contable-saldo-total .contable-saldo-valor {
        color: white;
        font-size: 1.7rem;
    }

    .contable-saldo-tooltip {
        cursor: help;
        color: var(--info-color);
        font-size: 0.8rem;
        transition: color 0.2s;
    }

    .contable-saldo-tooltip:hover {
        color: var(--primary-color);
    }

    .contable-saldo-card.contable-saldo-total .contable-saldo-tooltip {
        color: rgba(255, 255, 255, 0.8);
    }

    .contable-saldo-card.contable-saldo-total .contable-saldo-tooltip:hover {
        color: white;
    }

    @media (max-width: 768px) {
        .contable-saldos-resumen {
            grid-template-columns: 1fr;
        }
        
        .contable-saldo-valor {
            font-size: 1.3rem;
        }
        
        .contable-saldo-card.contable-saldo-total .contable-saldo-valor {
            font-size: 1.5rem;
        }
    }
</style>
