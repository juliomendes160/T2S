<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <?php include "configuracao.php";?>
        <title>Relatório</title>
    </head>
    <body>
        <header>
            <?php include "cabecalho.php";?>
        </header>

        <main>
        <?php
            $servername = "localhost:3308";
            $username = "root";
            $password = "";
            $dbname = "T2S";
        
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            
            if (!$conn) {
                die(mysqli_connect_error());
            }
            
            if(empty($_POST['movimentacaoOrdenar'])){
                $sql = "SELECT movimentacao, fkNumeroConteiner, COUNT(*) AS quantidade FROM movimentacao GROUP BY movimentacao ORDER BY quantidade";
                $result = mysqli_query($conn, $sql);
            }
            elseif($_POST['movimentacaoOrdenar'] != "cliente"){
                $sql = "SELECT movimentacao, fkNumeroConteiner, COUNT(*) AS quantidade FROM movimentacao GROUP BY movimentacao ORDER BY ".$_POST['movimentacaoOrdenar']."";
                $result = mysqli_query($conn, $sql);
            }
            else{
                $sql = "SELECT numeroConteiner, cliente FROM conteiner ORDER BY ".$_POST['movimentacaoOrdenar']."";
                $result = mysqli_query($conn, $sql);
            }
                echo '<div class="tabela">';
                echo '<h1>Total de Movimentações</h1>';
                    echo '<table>';
                        echo '<thead>';
                            echo '<tr>';
                                echo '<th>Cliente</th>';
                                echo '<th>Quantidade</th>';
                                echo '<th>Movimentação</th>';
                            echo '</tr>';
                        echo '</thead>';
                       
                        while(($row = mysqli_fetch_assoc($result))){
                        
                        if(empty($_POST['movimentacaoOrdenar'])){
                            $sql2 = "SELECT * FROM conteiner WHERE numeroConteiner='".$row['fkNumeroConteiner']."'";
                            $result2 = mysqli_query($conn, $sql2);
                            $row2 = mysqli_fetch_assoc($result2);
                        }
                        elseif($_POST['movimentacaoOrdenar'] != "cliente"){
                            $sql2 = "SELECT * FROM conteiner WHERE numeroConteiner='".$row['fkNumeroConteiner']."'";
                            $result2 = mysqli_query($conn, $sql2);
                            $row2 = mysqli_fetch_assoc($result2);
                        }
                        else{
                            $row2 = $row;
                            $sql2 = "SELECT movimentacao, fkNumeroConteiner, COUNT(*) AS quantidade FROM movimentacao WHERE fkNumeroConteiner='".$row['numeroConteiner']."'";
                            $result2 = mysqli_query($conn, $sql2);
                            $row = mysqli_fetch_assoc($result2);
                            
                        }
                        
                        echo '<tbody>';
                            echo '<tr>';
                                echo '<td>'.$row2['cliente'].'</td>';
                                echo '<td>'.$row['quantidade'].'</td>';
                                echo '<td>'.$row['movimentacao'].'</td>';
                            echo '</tr>';
                        echo '</tbody>';}
                    echo '</table>';
                    echo'
                    <form action="movimentacaoRelatorio.php" method="post">
                        <div class="pesquisar">
                            
                            <select name="movimentacaoOrdenar" class="ordenar" required>
                                <option value="">Ordenar</option>
                                <option Value="quantidade">Quantidade</option>
                                <option value="movimentacao">Movimentação</option>
                            </select>

                            <section class="filtrar">
                                <input type="submit" value="Aplicar" >
                            </section>
                        </div>
                    </form>
                ';
                echo '</div>';
                
                if(!empty($_POST['categoriaOrdenar'])){
                    $sql = "SELECT cliente, categoria, COUNT(*) AS quantidade FROM conteiner GROUP BY categoria ORDER BY  ".$_POST['categoriaOrdenar']."";
                    $result = mysqli_query($conn, $sql);
                }else{
                    $sql = "SELECT cliente, categoria, COUNT(*) AS quantidade FROM conteiner GROUP BY categoria ORDER BY categoria";
                    $result = mysqli_query($conn, $sql);
                }
                
                echo'<div class="tabela">
                    <table>
                        <h1>Total Importações e Exportações</h1>
                        
                        <thead>
                            <tr> 
                                <th>Quantidade</th>
                                <th>Categoria</th>
                            </tr>
                        </thead>';
                    
                        while(($row = mysqli_fetch_assoc($result))){
                        echo'<tbody>
                            <tr> 
                                <td>'.$row['quantidade'].'</td>
                                <td>'.$row['categoria'].'</td>
                            </tr>
                        </tbody>';
                        }
                    
                    echo'</table>
                    
                    <form action="movimentacaoRelatorio.php" method="post">
                        <div class="pesquisar">
                            
                            <select name="categoriaOrdenar" class="ordenar" required>
                                <option value="">Ordenar</option>
                                <option Value="quantidade">Quantidade</option>
                                <option value="categoria">Categoria</option>
                            </select>

                            <section class="filtrar">
                                <input type="submit" value="Aplicar" >
                            </section>
                        </div>
                    </form>
                </div>';
                
            mysqli_close($conn);
        ?>
        </main>

        <footer>
            <?php include "rodape.php";?>
        </footer>
    </body>
</html>