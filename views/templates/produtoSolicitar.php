<?php // mostrar os produtos disponíveis para solicitar ?>
<div class="container">
    <h1>Solicitação de Produtos</h1>

    <?php if (isset($_SESSION['mensagem_confirmacao'])): ?>
        <script type="text/javascript">
            window.onload = function() {
                alert('<?php echo htmlspecialchars($_SESSION['mensagem_confirmacao']); ?>');
            };
        </script>
        <?php unset($_SESSION['mensagem_confirmacao']); // Limpa a mensagem após exibir ?>
    <?php endif; ?>
    <div class="top-bar">
        <form method="GET" action="/Produto/" class="search-form">
            <input type="text" name="search" placeholder="Buscar por nome" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="search-input">
            <button type="submit" class="search-button">Buscar</button>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Situação</th>
                <th>Saldo</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($produtos) && !empty($produtos)): ?>
                <?php foreach ($produtos as $row): ?>
                    <?php
                        $nome = htmlspecialchars($row['nome']);
                        $categoria = htmlspecialchars($row['categoria']);
                        $situacao = htmlspecialchars($row['situacao']);
                        $saldo = htmlspecialchars($row['saldo_final']);
                    ?>
                    <tr>
                        <td><?php echo $nome; ?></td>
                        <td><?php echo $categoria; ?></td>
                        <td><?php echo $situacao; ?></td>
                        <td><?php echo $saldo; ?></td>
                        <td>
                            <form method="POST" action="Solicitacao/solicitar">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
                                <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>">
                                <input type="hidden" name="situacao" value="<?php echo htmlspecialchars($situacao); ?>">
                                <input type="hidden" name="saldo_final" value="<?php echo htmlspecialchars($saldo); ?>">
                                <button type="submit" class="btn-edit">Solicitar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum produto encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
