<?php

$arquivosRestaurantes = getArquivosRestaurantes();

$arrayArquivosCidades = explode('/home/ubuntu/www/alura/php_json/brasil', $arquivosRestaurantes);

unset($arrayArquivosCidades[0]);

$arquivo = $arrayArquivosCidades[40];

importaTodasAsCidades($arrayArquivosCidades);




//FUNCÕES

function importaTodasAsCidades($arrayArquivosCidades)
{
        foreach($arrayArquivosCidades as $arquivoCidade){

                $caminhoArquivo = '/home/ubuntu/www/alura/php_json/brasil'.$arquivoCidade;

                $mensagemImportacao = importaRestaurantesNoBanco($caminhoArquivo);

                echo '<pre>', $mensagemImportacao. ' Cidade -> '. $arquivoCidade;
        }

        die('teste');
}

function importaRestaurantesNoBanco($arquivo)
{
        $json = getConteudoArquivo($arquivo);
        
        $restaurantes = converteJsonEmArray($json);

        $mensagem = gravaNoBanco($restaurantes);
        
        return $mensagem;
}

function getConteudoArquivo($arquivo)
{
        //var_dump(trim($arquivo));die('tem erro aqui');
        return file_get_contents(trim($arquivo));
}

function getConteuDoArquivoDeTodasAsCidades()
{
        $lista_de_arquivos = getArquivosRestaurantes();
}

function getArquivosRestaurantes()
{
        return shell_exec('find /home/ubuntu/www/alura/php_json/brasil -type f');
}

function converteJsonEmArray($json)
{
        return json_decode($json, true);
}

function getConexao()
{
        return mysqli_connect('localhost', 'root', '123456', 'restaurantes');
}

function gravaNoBanco($restaurantes)
{
        if(!$restaurantes){
                return getImagemErro() . ' Não existe restaurantes para gravar no banco!';
        }

        foreach ($restaurantes as $value) {
                
                $context = $value['context'];
                $type = $value['type'];
                $name = $value['name'];
                $image = $value['image'];
                $priceRange = $value['priceRange'];
                $servesCuisine = $value['servesCuisine'];
                $url = $value['url'];
                $addresstype = $value['address']['type'];
                $addressLocality = $value['address']['addressLocality'];
                $addressRegion = $value['address']['addressRegion'];
                $addressCountry = $value['address']['addressCountry'];
        
                $query = "insert into restaurante (context, type, name, image, priceRange, servesCuisine, url, addresstype, addressLocality, addressRegion, addressCountry) 
                                values ('{$context}', '{$type}' , '{$name}', '{$image}', '{$priceRange}', '{$servesCuisine}', '{$url}', '{$addresstype}', '{$addressLocality}', '{$addressRegion}', '{$addressCountry}')";
        
                $conexao = getConexao();
                mysqli_query($conexao, $query);
        }

        return getImagemSucesso() .' Gravou restaurantes no banco.';
}

function getImagemSucesso()
{
        return getHtmlStatusImportacao('success-flat.png'); 
}

function getImagemErro()
{
        return getHtmlStatusImportacao('error-flat.png'); 
}

function getHtmlStatusImportacao($imagem)
{
        return '<img src="'.$imagem.'" alt="Smiley face" height="15" width="15">'; 
}

var_dump('Parabens seus registros foram salvos no banco!');die();

?>

