## COMO O PROGRAMA FUNCIONA:

### Este programa foi criado para estudar o envio e recebimento de arquivos json. Foi usado a  site viacep.com.br para receber uma string com algum endereço. 

### Este programa se inicia inserindo a biblioteca jQuery no mesmo, para que seja possível usar o método ajax. Onde, se pega o json de viacep e se envia este objeto para a função enviaParaPHP; a função enviaParaPHP, por sua vez, transforma o arquivo recebido de objeto para string. Enviando o mesmo, via ajax, para o arquivo 'index.php' pelo método POST com a chave texto; O arquivo index.php por sua vez, receberá a string passada, irá transforma-la em uma matriz associativa e irá adicionar a propriedade 'aluno' com o valor de 'Thiago Martins' a essa matriz. Posteriormente, convertendo essa matriz em string e enviado-a novamente para exemplo.html; Novamente no arquivo exemplo.html, caso tenha ocorrido tudo certo em index.html e se tenha recebido novamente, irá escrever o objeto com a nova propriedade no console do programa. Caso contrário, irá emitir alerta com o texto '[ERRO...] Ocorreu um erro inesperado'

--------

## INSTRUÇÕES PARA VER O RESULTADO:

### Ao abrir este programa, pressione a tecla F12 di seu teclado e vá no aba console, para ver o resultado.