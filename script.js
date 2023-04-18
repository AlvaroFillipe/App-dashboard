$(document).ready(() => {
	$("#documentacao").on("click",()=>{
        

        //ultilizando metodo get()
        $.post('documentacao.html', data =>{
            $("#pagina").html(data)
        })

     
    })

    $("#suporte").on("click",()=>{        
        $.post('suporte.html', data =>{
            $("#pagina").html(data)
        })
    })
        
    //AJAX
    $("#competencia").on("change", e =>{
        //capturando value da competencia e colocando em variaveis 
        let competencia = $(e.target).val(); 
        console.log(competencia);        

        //decarando AJAX
        $.ajax({

            //metodo da requisição,url,dados,success,erro

            type:'GET',
            url:'app.php',
            //passando dados para o tipo JSon
            dataType:'json',
            //incluindo value
            data:`competencia=${competencia }`,                 
            
            success: dados =>{
                console.log(dados)
                $("#numeroVendas").html(dados['numeroVendas'])
                $("#totalVendas").html(dados['totalVendas'])
                $("#clientesAtivos").html(dados['clientesAtivos'])
                $("#clientesInativos").html(dados['clientesInativos'])
                $("#totalReclamacoes").html(dados['totalReclamacoes'])
                $("#totalElogios").html(dados['totalElogios'])
                $("#totalSugestões").html(dados['totalSugestões'])
                $("#totalDespesas").html(dados['totalDespesas'])
                
                
                console.log(dados['numeroVendas'])
                console.log(dados['totalVendas'])
                console.log(dados['clientesAtivos'])
                console.log(dados['clientesInativos'])
                console.log(dados['totalReclamacoes'])
                console.log(dados['totalElogios'])
                console.log(dados['totalSugestões'])
                console.log(dados['totalDespesas'])
                
            },
            error: erro =>{console.log(erro)}
        })
    })
})