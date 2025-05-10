<?php include 'views/partials/header.php'; ?>
<?php include 'views/partials/navbar.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1>Liste des questions</h1>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Question</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $question): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($question['Id_question']); ?></td>
                        <td><?php echo htmlspecialchars($question['text']); ?></td>
                        <td>
                            <a href="index.php?action=editQuestion&id=<?php echo $question['Id_question']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                            <a href="index.php?action=deleteQuestion&id=<?php echo $question['Id_question']; ?>" class="btn btn-sm btn-danger">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'views/partials/footer.php'; ?>