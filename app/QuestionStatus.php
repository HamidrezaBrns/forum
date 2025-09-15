<?php

namespace App;

enum QuestionStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case DRAFT = 'draft';
}
