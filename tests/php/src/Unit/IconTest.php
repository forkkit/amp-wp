<?php

namespace AmpProject\AmpWP\Tests\Unit;

use AmpProject\AmpWP\Icon;
use AmpProject\AmpWP\Tests\AssertContainsCompatibility;
use PHPUnit\Framework\TestCase;

final class IconTest extends TestCase {

	use AssertContainsCompatibility;

	/** @return array */
	public function get_icon_types() {
		$types = [
			'invalid',
			'link',
			'valid',
			'warning',
			'logo',
		];

		$data = [];
		foreach ( $types as $type ) {
			$data[ $type ] = [ $type ];
		}
		return $data;
	}

	/**
	 * @param string $type Icon type.
	 * @dataProvider get_icon_types
	 * @covers Icon::__construct()
	 * @covers Icon::invalid()
	 * @covers Icon::link()
	 * @covers Icon::valid()
	 * @covers Icon::warning()
	 * @covers Icon::logo()
	 * @covers Icon::get_color()
	 * @covers Icon::to_html()
	 */
	public function test_types( $type ) {
		/** @var Icon $icon */
		$icon = Icon::$type();
		$this->assertInstanceOf( Icon::class, $icon );

		$this->assertInternalType( 'string', $icon->get_color() );

		$html = $icon->to_html();
		$this->assertStringStartsWith( '<span ', $html );
		$this->assertStringEndsWith( '</span>', $html );
		$this->assertStringContains( "class=\"amp-icon amp-{$type}\"", $html );

		$html = $icon->to_html(
			[
				'id'          => 'amp-admin-bar-item',
				'class'       => '" onclick="alert(\"evil\")">end',
				'onmouseover' => 'alert("BAD")',
			]
		);
		$this->assertStringContains( "class=\"&quot; onclick=&quot;alert(\&quot;evil\&quot;)&quot;&gt;end amp-icon amp-{$type}\"", $html );
		$this->assertStringContains( 'id="amp-admin-bar-item"', $html );
		$this->assertStringNotContains( 'onmouseover', $html );
		$this->assertStringEndsWith( '</span>', $html );
	}
}
