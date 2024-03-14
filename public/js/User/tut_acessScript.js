function ActivarPizarraClick(){
    if (document.getElementById('pnlPizarra').style.visibility == 'visible'){
        document.getElementById('pnlPizarra').style.visibility = 'hidden';
        document.getElementById('btnPizarra').textContent = 'Activar Pizarra';
    } else {
        document.getElementById('pnlPizarra').style.visibility = 'visible';
        document.getElementById('btnPizarra').textContent = 'Desactivar Pizarra';
    }
}
