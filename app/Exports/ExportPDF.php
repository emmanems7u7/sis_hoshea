<?php

namespace App\Exports;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Response;

class ExportPDF
{
    public static function exportPdf(
        string $view,
        array $data,
        string $filenameBase,
        bool $download = true,
        array $mpdfConfig = []
    ): Response {
        $html = View::make($view, $data)->render();

        $mpdf = new \Mpdf\Mpdf($mpdfConfig);

        // Footer con numeración de página centrado
        $mpdf->SetHTMLFooter('
        <div style="border-top: 1px solid #ccc; padding-top: 5px; font-size: 10px;">
            <table width="100%">
                <tr>
                    <td style="text-align: left; color: #555;">
                        ' . e(env('APP_NAME', 'Sistema')) . '
                    </td>
                    <td style="text-align: right; color: #555;">
                        Página {PAGENO} de {nb}
                    </td>
                </tr>
            </table>
        </div>
    ');

        // Escribir el contenido HTML
        $mpdf->WriteHTML($html);

        $filename = $filenameBase . '_' . now()->format('Y-m-d_H-i') . '.pdf';

        $destination = $download
            ? \Mpdf\Output\Destination::DOWNLOAD
            : \Mpdf\Output\Destination::INLINE;

        if ($download) {
            return response(
                $mpdf->Output($filename, $destination)
            )
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        } else {
            $pdfContent = $mpdf->Output($filename, \Mpdf\Output\Destination::STRING_RETURN);

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename={$filename}");
        }
    }

}
