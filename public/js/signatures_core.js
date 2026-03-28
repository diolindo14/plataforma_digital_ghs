// signatures_core.js - Lógica Mestra de Assinatura (GHS Profissional)
// O Segredo do 100% funcional (Redimensionamento)

let signaturePadProfessor = null;
let signaturePadCert = null;

/**
 * FUNÇÃO MESTRA PARA REDIMENSIONAR (Compensação de DPI)
 */
function setupCanvas(canvas, padInstance) {
    if (!canvas || !padInstance) return;
    
    // Pequeno atraso para garantir que o elemento está 100% visível (especialmente em modais)
    setTimeout(() => {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        const ctx = canvas.getContext("2d");
        ctx.setTransform(1, 0, 0, 1, 0, 0); // Reset transform antes de escalar
        ctx.scale(ratio, ratio);
        padInstance.clear(); 
    }, 100);
}

/**
 * OPÇÕES DE SUAVIZAÇÃO (Assinatura Amigável)
 */
const signatureOptions = {
    backgroundColor: 'rgba(255, 255, 255, 0)',
    penColor: 'rgb(0, 0, 0)',
    minWidth: 0.5,
    maxWidth: 2.0, // Reduzido de 2.5 para um traço mais fino e elegante
    velocityFilterWeight: 0.7
};

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
