<?php
    //classe dashboard
    class Dashboard {
        public $data_inicio;
        public $data_fim;
        public $numeroVendas;
        public $totalVendas;
        public $totalDespesas;
        public $clientesAtivos;
        public $clientesInativos;
        public $totalReclamacoes;
        public $totalElogios;
        public $totalSugestões;
        
        
        
        //declarando o GET magico kkkk
        public function __get($atributo){
            return $this->$atributo;
        }

        //declarando o SET magio kkk
        public function __set($atributo,$valor){
            $this->$atributo = $valor;
            return $this;
        }
    }
    

    //classe de conceção com banco de dados
    class Conexao{
        private $host = '127.0.0.1';
        private $dbName = 'dashboard';
        private $user = 'root';
        private $pass = '';

        //declarando função para conectarr com banco de dados
        public function conectar(){
            try {
                $conn = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbName",
                    "$this->user",
                    "$this->pass"
                );
                //fazendo com que a instancia da aplicação ultilize a utf8
                $conn->exec('set charset utf8');
                return $conn;

                //pegando possivel mensagem de erro
            } catch (PDOException $e) {
                echo '<p>' . $e->getMessage() . '<p/>';
            }
        }

    }
    
    //class (model)
    class Bd{
        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard){
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        //função que vai pegar o numero de vendas
        public function getNumeroVendas(){
            $query = 'SELECT COUNT(*) as numero_vendas FROM tb_vendas WHERE data_venda BETWEEN :data_inicio and :data_fim';
            
            
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            //retornando resultado
            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
        }

        //funcao que vai pegar  o total de vendas e mostrar na tela
        public function getTotalVendas(){
            $query = 'SELECT SUM(total) as total_vendas FROM tb_vendas WHERE data_venda BETWEEN :data_inicio and :data_fim';
            
            
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            //retornando resultado pelo objeto total vendas
            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
        }

        //função que vai somar as despesas e coloca las na tela
        public function getDespesas(){
            //o "as" serve para fazer um alias para comportar o resultado da conta do SUM
            $query = 'SELECT SUM(total) as total_despesas FROM tb_despesas WHERE data_despesa BETWEEN :data_inicio and :data_fim';
            
            
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
            $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
            $stmt->execute();

            //retornando resultado
            return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;
        }
            
        //função que vai pegar clientes ativos
        public function getClientesAtivos(){
            $query = 'SELECT SUM(cliente_ativo) as total_cliente_ativo FROM tb_clientes WHERE cliente_ativo = 1;';
            
            
            $stmt = $this->conexao->prepare($query);            
            $stmt->execute();

            //retornando resultado
            return $stmt->fetch(PDO::FETCH_OBJ)->total_cliente_ativo;
        }
        
        //função que vai pegar clientes inativos        
        public function getClientesInativos(){
            $query = 'SELECT COUNT(id) as total_cliente_inativo FROM tb_clientes WHERE cliente_ativo = 0';
            
            
            $stmt = $this->conexao->prepare($query);            
            $stmt->execute();

            //retornando resultado
            return $stmt->fetch(PDO::FETCH_OBJ)->total_cliente_inativo;
        }
       

        //função que vai pegar clientes ativos
        public function getReclamacoes(){
            $query = 'SELECT SUM(tipo_contato) as total_reclamacoes FROM tb_contatos WHERE tipo_contato = 1';
            
            
            $stmt = $this->conexao->prepare($query);            
            $stmt->execute();

            //retornando resultado
            return $stmt->fetch(PDO::FETCH_OBJ)->total_reclamacoes;
        }

        //função que vai pegar clientes ativos
        public function getElogios(){
            $query = 'SELECT SUM(tipo_contato) as total_elogios FROM tb_contatos WHERE tipo_contato = 2';
            
            
            $stmt = $this->conexao->prepare($query);            
            $stmt->execute();

            //retornando resultado
            return $stmt->fetch(PDO::FETCH_OBJ)->total_elogios;
        }

        //função que vai pegar clientes ativos
        public function getSugestoes(){
            $query = 'SELECT SUM(tipo_contato) as total_sugestoes FROM tb_contatos WHERE tipo_contato = 3';
            
            
            $stmt = $this->conexao->prepare($query);            
            $stmt->execute();

            //retornando resultado
            return $stmt->fetch(PDO::FETCH_OBJ)->total_sugestoes;
        }        
        
    }

    /*variaveis que estao relacionadas com Datas*/

        //districhando ano e mes que esta sendo selecionado
        $competencia = explode('-', $_GET['competencia']);
        $ano = $competencia[0];
        $mes = $competencia[1];
        //clanculando quantos dias tem naqueke mes daquele respectivo ano
        $dias_do_mes = cal_days_in_month(CAL_GREGORIAN,$mes,$ano);

        $dashboard->__set('data_inicio',$ano.'-'.$mes.'-'.'01');

        //o banco de dados mysql exige que o caractere que separa o ano do dia do mes é o traço(ou ifem)"-"
        $dashboard->__set('data_fim', $ano.'-'.$mes.'-'.$dias_do_mes);  

    
    
    //logica do script
    $ashboard = new Dashboard();   
    $conexao = new Conexao();    
    $bd = new Bd($conexao, $dashboard);

    //setando com o metodo magico __set
    $dashboard->__set('numeroVendas', $bd->getNumeroVendas());
    $dashboard->__set('totalVendas', $bd->getTotalVendas());
    $dashboard->__set('clientesAtivos', $bd->getclientesAtivos());
    $dashboard->__set('clientesInativos', $bd->getclientesInativos());
    $dashboard->__set('totalReclamacoes', $bd->getReclamacoes());
    $dashboard->__set('totalElogios', $bd->getElogios());
    $dashboard->__set('totalSugestões', $bd->getSugestoes());
    $dashboard->__set('totalDespesas', $bd->getDespesas());
       
    
    //imprimindo super global $_GET com data        
    print_r($anoVenda.'/'.$mesVenda.'/'.$dias_do_mes);

    
    //imprimmindo da forma certa a data Json
    echo json_encode($dashboard);

    //imprimindo super global $_GET
    print_r($_GET);
    print_r($dashboard);
    print_r($competencia);


  
     





?>