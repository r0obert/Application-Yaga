<?php if(!defined('APPLICATION')) exit();
include_once 'interface.yagarule.php';
/**
 * This rule awards badges if a comment is placed on a new member's first discussion
 *
 * @author Zachary Doll
 * @since 1.0
 * @package Yaga
 */
class NewbieComment implements YagaRule{

  public function Award($Sender, $User, $Criteria) {
    $Discussion = $Sender->EventArguments['Discussion'];
    $NewbUserID = $Discussion->InsertUserID;
    $CurrentDiscussionID = $Discussion->DiscussionID;
    $TargetDate = strtotime($Criteria->Duration . ' ' . $Criteria->Period . ' ago');
    
    $SQL = Gdn::SQL();
    $FirstDiscussion = $SQL->Select('DiscussionID, DateInserted')
            ->From('Discussion')
            ->Where('InsertUserID', $NewbUserID)
            ->OrderBy('DateInserted')
            ->Get()
            ->FirstRow();
    
    $InsertDate = strtotime($FirstDiscussion->DateInserted);
    
    if($CurrentDiscussionID == $FirstDiscussion->DiscussionID
            && $InsertDate > $TargetDate) {
      return $User->UserID;
    }
    else {
      return FALSE;
    }
  }
    
  public function Form($Form) {
    $Lengths = array(
        'day' => T('Days'),
        'week' => T('Weeks'),
        'year' => T('Years')
    );
    
    $String = $Form->Label(T('User Newbness'), 'NewbieComment');
    $String .= $Form->Textbox('Duration');
    $String .= $Form->DropDown('Period', $Lengths);

    return $String; 
  }
  
  public function Hooks() {
    return array('CommentModel_BeforeNotification');
  }
  
  public function Description() {
    $Description = T('This rule checks if a comment is placed on a newbs first discussion. If it is, this will return true.');
    return $Description;
    
  }
  
  public function Name() {
    return T("Comment on New User's Discussion");
  }
}
