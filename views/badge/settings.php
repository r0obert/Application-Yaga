<?php if (!defined('APPLICATION')) exit();
/* Copyright 2013 Zachary Doll */

$Rules = $this->Data('Rules');

echo Wrap($this->Title(), 'h1');
echo Wrap(Wrap(T('Yaga.Badges.Settings.Desc'), 'div'), 'div', array('class' => 'Wrap'));
echo Wrap(Anchor(T('Yaga.AddBadge'), 'badge/add', array('class' => 'SmallButton')), 'div', array('class' => 'Wrap'));

?>
<table id="Badges" class="AltRows">
  <thead>
    <tr>
      <th><?php echo T('Image'); ?></th>
      <th><?php echo T('Name'); ?></th>
      <th><?php echo T('Description'); ?></th>
      <th><?php echo T('Rule'); ?></th>
      <th><?php echo T('Award Value'); ?></th>
      <th><?php echo T('Active'); ?></th>
      <th><?php echo T('Options'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $Alt = 'Alt';
    foreach($this->Data('Badges') as $Badge) {
      $Alt = $Alt ? '' : 'Alt';
      $Row = '';

      if($Badge->Photo) {
        $BadgePhoto = Img(Gdn_Upload::Url($Badge->Photo), array('class' => 'BadgePhoto'));
      }
      else {
        $BadgePhoto = Img('applications/yaga/design/images/default_badge.png', array('class' => 'BadgePhoto'));
      }

      $Row .= Wrap(Anchor($BadgePhoto, '/badges/detail/' . $Badge->BadgeID . '/' . Gdn_Format::Url($Badge->Name), array('title' => T('Yaga.Badge.DetailLink'))), 'td');
      $Row .= Wrap($Badge->Name, 'td');
      $Row .= Wrap($Badge->Description, 'td');
      $Row .= Wrap($Rules[$Badge->RuleClass], 'td');
      $Row .= Wrap($Badge->AwardValue, 'td');
      $ToggleText = ($Badge->Enabled) ? T('Enabled') : T('Disabled');
      $ActiveClass = ($Badge->Enabled) ? 'Active' : 'InActive';
      $Row .= Wrap(Wrap(Anchor($ToggleText, 'badge/toggle/' . $Badge->BadgeID, 'Hijack SmallButton'), 'span', array('class' => "ActivateSlider ActivateSlider-{$ActiveClass}")), 'td');
      $Row .= Wrap(Anchor(T('Edit'), 'badge/edit/' . $Badge->BadgeID, array('class' => 'SmallButton')) . Anchor(T('Delete'), 'badge/delete/' . $Badge->BadgeID, array('class' => 'Danger Popup SmallButton')), 'td');
      echo Wrap($Row, 'tr', array('id' => 'BadgeID_' . $Badge->BadgeID, 'data-badgeid' => $Badge->BadgeID, 'class' => $Alt));
    }
    ?>
  </tbody>
</table>
<?php PagerModule::Write(array('Sender' => $this));
