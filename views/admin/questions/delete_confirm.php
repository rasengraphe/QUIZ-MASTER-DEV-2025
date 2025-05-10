<!DOCTYPE html>
<html>
<head>
    <title>Supprimer une question</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h2>Confirmer la suppression</h2>
                </div>
                <div class="card-body">
                    <h5>Êtes-vous sûr de vouloir supprimer cette question ?</h5>
                    
                    <div class="alert alert-warning mt-3">
                        <strong>ID :</strong> <?php echo htmlspecialchars($question['Id_question']); ?><br>
                        <strong>Question :</strong> <?php echo htmlspecialchars($question['text']); ?>
                        
                        <?php if (!empty($question['picture'])): ?>
                        <div class="mt-2">
                            <strong>Image de la question</strong><br>
                            <img src="<?php echo htmlspecialchars($question['picture']); ?>" class="img-fluid img-thumbnail" style="max-height: 200px;">
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="alert alert-danger">
                        <strong>Attention :</strong> Cette action est irréversible et supprimera également toutes les réponses associées à cette question.
                    </div>
                    
                    <form method="post" action="index.php?action=deleteQuestion&id=<?php echo htmlspecialchars($question['Id_question']); ?>">
                        <input type="hidden" name="confirm_delete" value="yes">
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php?action=questions" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
