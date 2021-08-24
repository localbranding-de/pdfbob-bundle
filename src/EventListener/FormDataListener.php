<?php
namespace LocalbrandingDe\PdfbobBundle\EventListener;
use Contao\Form;
use Dompdf\Dompdf;
use \Email;
class FormDataListener {
    
    public function __construct() {} // eventuell nicht nötig, probieren
    
    public function __invoke(
        array $submittedData,
        array $formData,
        ?array $files,
        array $labels,
        Form $form
        ): void
        {
        
           
            if($form->id==4)
            {
                $objTemplate = new \FrontendTemplate("Meldeschein");
                $objTemplate->submittedData = $submittedData;
                foreach($submittedData as $key=>$data)
                {
                    
                    $objTemplate->{$key}= $data;
                }
                
                
                
                $html =  $objTemplate->parse();
        

                
 
                // reference the Dompdf namespace
        
                $path= "/html/contao/files/test.pdf";
                // instantiate and use the dompdf class
                $dompdf = new Dompdf();
                $dompdf->getOptions()->setChroot('/html/contao/files');
                $dompdf->loadHtml($html);
            
                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');
                
                // Render the HTML as PDF
                $dompdf->render();
                
                // Output the generated PDF to Browser
                $rpdf=$dompdf->output();
                file_put_contents($path,$rpdf);
                $objEmail = new \Email();
                $objEmail->from = "e@mail.com";
                $objEmail->subject = "Meldeschein";
                $objEmail->text = "TOBEREPLACED";
                
                $objEmail->fromName = "Meldeschein";
                
                $objEmail->attachFile($path);
                
                $objEmail->sendTo("e@mail.com");
                unlink($path);
            }
    }
    
    
    
}
