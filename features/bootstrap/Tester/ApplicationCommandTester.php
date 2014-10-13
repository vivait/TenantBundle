<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\StreamOutput;

class ApplicationCommandTester
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var StreamOutput
     */
    private $output;

    /**
     * Gets application
     * @return Application
     */
    public function getApplication() {
        return $this->application;
    }

    /**
     * Sets application
     * @param Application $application
     * @return $this
     */
    public function setApplication( Application $application ) {
        $this->application = $application;
        return $this;
    }

    /**
     * Gets input
     * @return InputInterface
     */
    public function getInput() {
        return $this->input;
    }

    /**
     * Gets output
     * @return StreamOutput
     */
    public function getOutput() {
        return $this->output;
    }

    function __construct( Application $application ) {
        $this->application = $application;
    }

    /**
     * Executes the command.
     *
     * Available options:
     *
     *  * interactive: Sets the input interactive flag
     *  * decorated:   Sets the output decorated flag
     *  * verbosity:   Sets the output verbosity flag
     *
     * @param array $input   An array of arguments and options
     * @param array $options An array of options
     *
     * @return int     The command exit code
     */
    public function execute(array $input, array $options = array())
    {
        // set the command name automatically if the application requires
        // this argument and no command name was passed
        if (!isset($input['command'])) {
            throw new RuntimeException('No command passed to ApplicationCommandTester');
        }

        $this->input = new ArrayInput($input);
        if (isset($options['interactive'])) {
            $this->input->setInteractive($options['interactive']);
        }

        $this->output = new StreamOutput(fopen('php://memory', 'w', false));
        if (isset($options['decorated'])) {
            $this->output->setDecorated($options['decorated']);
        }
        if (isset($options['verbosity'])) {
            $this->output->setVerbosity($options['verbosity']);
        }

        return $this->statusCode = $this->application->doRun($this->input, $this->output);
    }

    /**
     * Gets the display returned by the last execution of the command.
     *
     * @param bool    $normalize Whether to normalize end of lines to \n or not
     *
     * @return string The display
     */
    public function getDisplay($normalize = false)
    {
        rewind($this->output->getStream());

        $display = stream_get_contents($this->output->getStream());

        if ($normalize) {
            $display = str_replace(PHP_EOL, "\n", $display);
        }

        return $display;
    }
}
