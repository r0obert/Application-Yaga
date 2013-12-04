<?php if(!defined('APPLICATION')) exit();
include_once 'interface.yagarule.php';
/**
 * This rule awards badges based on a user's received reactions
 *
 * @author Zachary Doll
 * @since 1.0
 * @package Yaga
 */
class ReactionCount implements YagaRule{
  
  public function Award($Sender, $User, $Criteria) {
    $ActionID = $Sender->EventArguments['ActionID'];
    
    if($Criteria->ActionID != $ActionID) {
      return FALSE;
    }
    
    $ReactionModel = new ReactionModel();
    $Count = $ReactionModel->GetUserReactionCount($Sender->EventArguments['ParentUserID'], $ActionID);
    
    if($Count >= $Criteria->Target) {
      // Award the badge to the user that got the reaction
      return $Sender->EventArguments['ParentUserID'];
    }
    else {
      return FALSE;
    }
  }
  
  public function Form($Form) {
    $ActionModel = new ActionModel();
    $Actions = $ActionModel->GetActions();
    $Reactions = array();
    foreach($Actions as $Action) {
      $Reactions[$Action->ActionID] = $Action->Name;
    }
    
    $String = $Form->Label(T('Total reactions'), 'ReactionCount');
    $String .= T('User has ');
    $String .= $Form->Textbox('Target');
    $String .= $Form->DropDown('ActionID', $Reactions);
    
    return $String;
  }
  
  public function Hooks() {
    return array('ReactionModel_AfterReactionSave');
  }
  
  public function Description() {
    $Description = T('This rule checks a users reaction count against the target. It will return true once the user has as many or more than the given reactions count.');
    return $Description;
    
  }
  
  public function Name() {
    return T('Reaction Count Total');
  }
}
