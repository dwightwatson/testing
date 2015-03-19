<?php namespace Watson\Testing;

trait ControllerHelpers {

    /**
     * Assert that the provided view template is the one rendered by the action.
     *
     * @param  string $expectedView
     * @param  string $message
     * @return void
     */
    public function assertViewIs($expectedView, $message = null)
    {
        $actualView = $this->response->getOriginalContent()->getName();

        $this->assertEquals($expectedView, $actualView, $message);
    }

}
