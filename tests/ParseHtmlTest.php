<?php
use PHPUnit\Framework\TestCase;

/**
 * @covers ParseHtml
 */
class ParseHtmlTest extends TestCase
{
    public function testParseTags()
    {
        $parser = new ParseHtml($this->getMockHtml());
        $tags = $parser->parse();

        $this->assertContains('stylesheet-file.css', $tags);
        $this->assertContains('javascript-file.js', $tags);
        $this->assertContains('image-file.png', $tags);
        $this->assertContains('audio-file.mp3', $tags);
        $this->assertContains('video-file.mp4', $tags);
        $this->assertContains('object-file.swf', $tags);
        $this->assertContains('embed-file.swf', $tags);
    }

    /**
     * @return string
     */
    private function getMockHtml()
    {
        return <<<HTML
<html>
<head>
    <link href="stylesheet-file.css">
    <script src="javascript-file.js"></script>
</head>
<body>
    <img src="image-file.png">
    <audio>
        <source src="audio-file.mp3">
    </audio>
    <video>
        <source src="video-file.mp4">
    </video>
    <object data="object-file.swf"></object>
    <embed src="embed-file.swf">
</body>
</html>
HTML;
    }
}
