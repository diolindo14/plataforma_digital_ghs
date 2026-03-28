// signatures_core.js - Lógica Mestra de Assinatura (GHS Profissional)
// O Segredo do 100% funcional (Redimensionamento)

let signaturePadProfessor = null;
let signaturePadCert = null;

/**
 * FUNÇÃO MESTRA PARA REDIMENSIONAR (Compensação de DPI)
 */
function setupCanvas(canvas, padInstance) {
    if (!canvas || !padInstance) return;
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    padInstance.clear(); 
}

/**
 * FUNÇÃO DE ENVIO AJAX (Persistência no Banco de Dados)
 */
function enviarAssinatura(pad, tipoPainel, userId) {
    if (!pad || pad.isEmpty()) {
        alert("Por favor, assine no campo primeiro!");
        return;
    }

    const dataSVG = pad.toDataURL('image/svg+xml');

    // Integração com salvar_ghs.php
    $.post('/green/public/salvar_ghs.php', {
        assinatura: dataSVG,
        user_id: userId,
        painel: tipoPainel
    }, function(response) {
        if(response.status === 'success') {
            alert("Sucesso: Assinatura gravada no Banco de Dados!");
            
            // Se estiver num modal, fechar após sucesso
            if($("#modalAssinaturaCertificado").is(':visible')) {
                bootstrap.Modal.getInstance(document.getElementById('modalAssinaturaCertificado')).hide();
                if (typeof loadCertificadosEmitidos === "function") loadCertificadosEmitidos();
            }
        } else {
            alert("Erro do Servidor: " + response.message);
        }
    }, 'json').fail(function() {
        alert("Erro Crítico: Não foi possível comunicar com o servidor de assinaturas.");
    });
}
