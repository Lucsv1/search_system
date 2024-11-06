// Evento ao clicar no butao de Aluno ele direciona para a pagina de login do aluno
document.getElementById('btnAluno')?.addEventListener('click', function(){
    window.location.href='public/login/page_login_to_user.php';
})

// Evento ao clicar no butao de Professor ele direciona para a pagina de login do professor
document.getElementById('btnProfessor')?.addEventListener('click', function(){
    window.location.href='public/login/page_login_to_teacher.php';
})
