<?php
require('fpdf/fpdf.php');
include ('includes/connect.php');
include ('includes/session.php');

header('Content-Type: text/html; charset=utf-8');

if (!isset($_GET['correct_count']) || !isset($_GET['total_count'])) {
    die('Error: Insufficient data to generate certificate.');
}

$correct_count = (int)$_GET['correct_count'];
$total_count = (int)$_GET['total_count'];

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial','B',15);
        $this->Cell(0,10,'Certificate of Quiz Completion',0,1,'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
    }

    function CertificateBody($correct_count, $total_count)
    {
        $this->SetFont('Arial','',12);
        $this->Cell(0,10,'You have successfully completed the quiz and answered '.$correct_count.' out of '.$total_count.' questions correctly.',0,1,'C');
        $this->Ln(20);
        $this->Cell(0,10,'Congratulations!',0,1,'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->CertificateBody($correct_count, $total_count);
$pdf->Output('D', 'certificate.pdf');
?>
