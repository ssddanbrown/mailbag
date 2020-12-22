<?php

namespace Tests\Unit;

use App\Models\SendRecord;
use App\Services\MailContentParser;
use Tests\TestCase;

class MailContentParserTest extends TestCase
{

    public function test_parse_for_send_adds_unsub_link_at_tag_if_existing()
    {
        $record = SendRecord::factory()->create();
        $content = "ABC {{unsubscribe_link}} DEF";

        $parser = new MailContentParser($content);
        $output = $parser->parseForSend($record);

        $this->assertEquals("ABC http://localhost/unsubscribe/{$record->key} DEF", $output);
    }

    public function test_parse_for_send_adds_unsub_link_at_end_if_no_tag()
    {
        $record = SendRecord::factory()->create();
        $content = "ABC DEF";

        $parser = new MailContentParser($content);
        $output = $parser->parseForSend($record);

        $this->assertEquals("ABC DEF\n\n" . "http://localhost/unsubscribe/{$record->key}", $output);
    }

}
