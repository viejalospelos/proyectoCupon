<?php

namespace Cupon\OfertaBundle\Twig\Extension;

use Symfony\Component\Translation\TranslatorInterface;


Class CuponExtension extends \Twig_Extension{
# Creacion de un MÉTODOS twig
	private $translator;
	
	public function __construct(TranslatorInterface $translator = null)
	{
		$this->translator = $translator;
	}
	
	public function getTranslator()
	{
		return $this->translator;
	}
	
	
	
	public function getFunctions(){
		return array(
				'descuento'=> new \Twig_Function_Method($this, 'descuento')
		);
	}	
	public function descuento ($precio, $descuento, $decimales=0){
		if (!is_numeric($precio)|| !is_numeric($descuento)){
			return '-';
		}
		if ($descuento==0 || $descuento== null){
			return '0%';
		}
		$precio_original= $precio + $descuento;
		$porcentaje=($descuento/$precio_original)*100;
		return '-'.number_format($porcentaje, $decimales).'%';
	}
# Creación de FILTROS twig	
	public function getFilters(){
		return array(
			'mostrar_como_lista'=> new \Twig_Filter_Method($this, 'mostrarComoLista', array('is_safe'=>array('html'))),
			'cuenta_atras'=> new \Twig_Filter_Method($this, 'cuentaAtras', array('is_safe'=>array('html'))),
			'fecha' => new \Twig_Filter_Method($this, 'fecha'),
		);
	}
	public function mostrarComoLista($value, $tipo='ul'){
		$html="<".$tipo.">\n";
		$html .= " <li>".str_replace("\n", "</li>", $value)."</li>\n";
		$html .= "</".$tipo.">\n";
		return $html;
	}
	public function cuentaAtras($fecha){
		$fecha=$fecha->format('Y,')
				.($fecha->format('m')-1)
				.($fecha->format(',d,H,i,s'));
		$html= <<<EOJ
<script type="text/javascript">
function muestraCuentaAtras(){
	var horas, minutos, segundos;
	var ahora = new Date();
	var fechaExpiracion = new Date($fecha);
	var falta = Math.floor( (fechaExpiracion.getTime() - ahora.getTime()) / 1000 );
	if (falta < 0) {
		cuentaAtras = '-';
	}
	else {
		horas = Math.floor(falta/3600);
		falta = falta % 3600;
		minutos = Math.floor(falta/60);
		falta = falta % 60;
		segundos = Math.floor(falta);
		cuentaAtras = (horas < 10 ? '0' + horas : horas) + 'h '
			+ (minutos < 10 ? '0' + minutos : minutos) + 'm '
			+ (segundos < 10 ? '0' + segundos : segundos) + 's ';
		setTimeout('muestraCuentaAtras()', 1000);
	}
document.getElementById('tiempo').innerHTML = '<strong>Faltan:</strong> ' + cuentaAtras;
		}
muestraCuentaAtras();
</script>
EOJ;
		return $html;
}
	
	public function getName(){
		return 'cupon';
	}
	
//EL FORMATEADOR DE FECHAS ME DA FALLOS. SE UTILIZA EN \BackendBundle\Resources\views\Usuario\index.html.twig
//EN LA LINEA 29 APARECE             <td>{{ entity.fechaalta|fecha(medium) }}</td>
//Y LO HE TENIDO QUE SUSTITUIR POR             <td>{{ entity.fechaalta|date }}</td>
	/**
	 * Formatea la fecha indicada según las características del locale seleccionado.
	 * Se utiliza para mostrar correctamente las fechas en el idioma de cada usuario.
	 *
	 * @param string $fecha        Objeto que representa la fecha original
	 * @param string $formatoFecha Formato con el que se muestra la fecha
	 * @param string $formatoHora  Formato con el que se muestra la hora
	 * @param string $locale       El locale al que se traduce la fecha
	 */
	public function fecha($fecha, $formatoFecha = 'medium', $formatoHora = 'none', $locale = null)
	{
		// Código copiado de
		//   https://github.com/thaberkern/symfony/blob
		//   /b679a23c331471961d9b00eb4d44f196351067c8
		//   /src/Symfony/Bridge/Twig/Extension/TranslationExtension.php
	
		// Formatos: http://www.php.net/manual/en/class.intldateformatter.php#intl.intldateformatter-constants
		$formatos = array(
				// Fecha/Hora: (no se muestra nada)
				'none'   => \IntlDateFormatter::NONE,
				// Fecha: 12/13/52  Hora: 3:30pm
				'short'  => \IntlDateFormatter::SHORT,
				// Fecha: Jan 12, 1952  Hora:
				'medium' => \IntlDateFormatter::MEDIUM,
				// Fecha: January 12, 1952  Hora: 3:30:32pm
				'long'   => \IntlDateFormatter::LONG,
				// Fecha: Tuesday, April 12, 1952 AD  Hora: 3:30:42pm PST
				'full'   => \IntlDateFormatter::FULL,
		);
	
		$formateador = \IntlDateFormatter::create(
				$locale != null ? $locale : $this->getTranslator()->getLocale(),
				$formatos[$formatoFecha],
				$formatos[$formatoHora]
				);
	
		if ($fecha instanceof \DateTime) {
			return $formateador->format($fecha);
		} else {
			return $formateador->format(new \DateTime($fecha));
		}
	}
	
}
