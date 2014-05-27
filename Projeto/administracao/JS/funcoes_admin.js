/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    function validarnovanoticia(){ 
        if(document.novanoticia.titulo.value==="")
        {
            alert("O Campo 'Título' é obrigatório!");
            return false;
        }
        else
        if(document.novanoticia.conteudo.value==="")
        {
            alert("O Campo 'Conteúdo' é obrigatório!");
            return false;
        }
        else
        return true;
    }
    
    function validaralterarnoticia(){ 
        if(document.alterarnoticia.titulo.value==="")
        {
            alert("O Campo 'Título' é obrigatório!");
            return false;
        }
        else
        if(document.alterarnoticia.conteudo.value==="")
        {
            alert("O Campo 'Conteúdo' é obrigatório!");
            return false;
        }
        else
        return true;
    }
    
    function validarnovagaleria(){ 
        if(document.novagaleria.imagem.value==="")
        {
            alert("A imagem é obrigatória!");
            return false;
        }
        else
        if(document.novagaleria.descricao.value==="")
        {
            alert("O Campo 'Descrição' é obrigatório!");
            return false;
        }
        else
        return true;
    }
    
    function validaralterargaleria(){ 
        if(document.alterargaleria.descricao.value==="")
        {
            alert("O Campo 'Descrição' é obrigatório!");
            return false;
        }
        else
        return true;
    }
    
    function validarnovapagina(){ 
        
        if(document.novapagina.nome.value==="")
        {
            alert("O Campo 'Nome' é obrigatório!");
            return false;
        }
        else
        if(document.novapagina.titulo.value==="")
        {
            alert("O Campo 'Título' é obrigatório!");
            return false;
        }
        else
        if(document.novapagina.conteudo.value==="")
        {
            alert("O Campo 'Conteúdo' é obrigatório!");
            return false;
        }
        else
        return true;
    }
    
    function validarrespondermensagem(){ 
        
        if(document.respondermensagem.resposta.value==="")
        {
            alert("A resposta tem que ser preenchida");
            return false;
        }
        else
        return true;
    }    
    
    function validaralterarupload(){ 
        
        if(document.alterarupload.categoria.value==="")
        {
            alert("O Campo 'Categoria' é obrigatório!");
            return false;
        }
        else
        if(document.alterarupload.titulo.value==="")
        {
            alert("O Campo 'Título' é obrigatório!");
            return false;
        }
        else
        if(document.alterarupload.descricao.value==="")
        {
            alert("O Campo 'Descrição' é obrigatório!");
            return false;
        }
        else
        return true;
    }
    
    function validaralterarutilizador(){ 
        
        if(document.alterarutilizador.username.value==="")
        {
            alert("O Campo 'Username' é obrigatório!");
            return false;
        }
        else
        if(document.alterarutilizador.pnome.value==="")
        {
            alert("O Campo 'Primeiro nome' é obrigatório!");
            return false;
        }
        else
        if(document.alterarutilizador.unome.value==="")
        {
            alert("O Campo 'Último nome' é obrigatório!");
            return false;
        }
        else
        if(document.alterarutilizador.email.value==="")
        {
            alert("O Campo 'Email' é obrigatório!");
            return false;
        }
        else
        if(document.alterarutilizador.pais.value==="")
        {
            alert("O Campo 'País' é obrigatório!");
            return false;
        }
        else
        if(document.alterarutilizador.permissoes.value==="")
        {
            alert("O Campo 'Permissões' é obrigatório!");
            return false;
        }
        else
        return true;
    }

function confirmar_apagarnoticia(){
    var r=confirm("Tem a certeza que a deseja apagar?");
    if (r===true)
      {
      return true;
      }
    else
      {
      return false;
      }
}