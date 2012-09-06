<?php


    $chemin_rep = 'poll/1/'.$_GET['part'].'.txt';    


    if($_GET['export']){
        
    header("content-type: application/vnd.ms-excel" );
    header('Content-Disposition: attachement; filename="export.csv"');     
    
    foreach (glob("rpoll/1/*.txt") as $filename) {
        echo file_get_contents($filename, true).'
            ';
    }    
    exit();    
    }
    
    
    if($_GET['part']=="") {
        echo'Impossible de prendre votre participation';
        exit();
    }    
    
    

    //  && glob($chemin_rep)==false && glob($chemin_rep)==false
    if(isset($_POST["send"])) {
        
    foreach($_POST['rep'] as $rep):
        foreach($rep as $r):
            $str .= $r.';';    
        endforeach;    
    endforeach;
    
    $str .= ';'.$_GET['part'];
    
    //echo $str;
    //echo'<pre>';
    //print_r($_POST);
    //echo'</pre>';
    
    $rep_file = fopen($chemin_rep, 'w+');
    fputs($rep_file, $str);
    fclose($rep_file);
    
    // basic & primitive reflex: vomit!
    mail('xxxxxxxxxxxx', 'Nouveau participant enquete '.$_GET['part'], $_GET['part']);
    
    echo 'Merci pour votre participation<br />'; // .$str
    exit();
    }

    if(glob($chemin_rep)==true) {
        echo'Vous avez déjà voté!';
        exit();
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////    

    $question = array('Pour quelles raisons  :', 
                      'Lorsque vous nous adressez des demandes d\'information par mail :', 
                      'Lorsque vous nous adressez des demandes d\'information par courrier :',
                      'Lorsque vous nous adressez des demandes d\'information par téléphone :',
                      'Vous jugez les  :',
                      'Vous jugez les  :',
                      'Le délai  :',
                      'La communication apparaît :',
                      'La restitution  :',
                      'Le délai :',
                      'Le :', 
                      'En :',
                      'En  :', 
                      'Quelle note globale sur 10 donneriez-vous ?'
        );
    
    $sub_question[1] = array(array(null, 'p'));
  
    $sub_question[2] = array(
                        array('a) Le délai des réponses que vous obtenez est en général', 'm'), 
                        array('b) La qualité des réponses que vous obtenez est en général', 'f'));
    
    $sub_question[3] = array(
                        array('a) Le délai des réponses que vous obtenez est en général', 'm'), 
                        array('b) La qualité des réponses que vous obtenez est en général', 'f'));
    
    $sub_question[4] = array(
                        array('a) Le délai des réponses que vous obtenez est en général', 'm'), 
                        array('b) La qualité des réponses que vous obtenez est en général', 'f'),
                        array('c) La qualité de l\'accueil est en général', 'f'));

    
    $sub_question[5] = array(array(null, 'm'));
    $sub_question[6] = array(array(null, 'm'));
    $sub_question[7] = array(array(null, 'm'));
    $sub_question[8] = array(array(null, 'm'));
    $sub_question[9] = array(array(null, 'm'));
    $sub_question[10] = array(array(null, 'm'));
    $sub_question[11] = array(array(null, 'm'));
    $sub_question[12] = array(array(null, 'm'));
    $sub_question[13] = array(array(null, 'm'));
    $sub_question[14] = array(array(null, 'i'));
    
    
    $reponse_m  = array('Très satisfaisant','Satisfaisant','Peu satisfaisant','Pas du tout satisfaisant');
    $reponse_f = array('Très satisfaisante','Satisfaisante','Peu satisfaisante','Pas du tout satisfaisante');
    $reponse_i = array('1','2','3','4','5','6','7','8','9','10');    
    $reponse_p = array('Parce que',
                       'Parce que',
                       'Parce que',
                       'Parce que',
                       'Parce que');
    
    $reponses  = array('m'=>$reponse_m, 'f'=>$reponse_f, 'i'=>$reponse_i, 'p'=>$reponse_p);

    
    
?>

<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Enquete</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="rpoll/validationEngine.jquery.css" type="text/css"/>
        <script src="rpoll/jquery.validationEngine-fr.js" type="text/javascript" charset="utf-8"></script>
        <script src="rpoll/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
        <script>
            jQuery(document).ready(function(){
                jQuery("#formID").validationEngine();
            });
        </script>
        <style>
        .formError .formErrorContent {                
            left:500px;
            top:45px;
        }
        </style>
    </head>
    <body> 
        

       
        
<h1>Quel est votre niveau de satisfaction : </h1>        
<form id="formID" method="post" action="#">   
    
    <?php foreach($question as $q): ?>  
    <p>
    <?php $iq++; ?>    
    <strong><?php echo $iq; ?>) <?php echo $q; ?></strong><br /> 
    
            <?php foreach($sub_question[$iq] as $sq):?>
            <?php echo $sq['0']; $isq++; if(isset($sq['0'])) echo'<br />'; ?>

                    <?php foreach($reponses[$sq['1']] as $r):?> 
                    <?php $ir++; ?>
                    <input class="validate[required] radio" type="radio" name="rep[<?php echo $iq; ?>][<?php echo $isq; ?>]" id="rep[<?php echo $iq; ?>][<?php echo $isq; ?>]" value="<?php echo $ir; ?>"/>
                    
                    <?php echo $r; ?>
                    <br />
                    <?php endforeach;
                    
                    
                    $ir = '0'; ?> 
            <?php endforeach;?>
            <?php //$iq = '0'; ?>
    </p>
    <?php endforeach;?>    

</fieldset>

    <strong>Remarques, suggestions : </strong><br />
    <textarea name="rep[15][<?php echo $isq++; ?>]" style="width:600px;height:200px;"></textarea>
    <br />
    
<input class="submit" name="send" type="submit" value="VALIDATION"/>  
</form>
    </body>
</html>
